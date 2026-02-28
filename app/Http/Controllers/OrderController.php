<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Supplier;
use App\Models\SupplierUser;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class OrderController extends BaseController
{
    public function index()
    {
        // Check permission
        $this->authorize('view_orders');
        
        $user = auth()->user();
        
        // Super admin sees all orders
        if ($user->isSuperAdmin()) {
            $orders = Order::with(['supplier', 'customer'])->latest()->paginate(20);
        } else {
            // Admin sees only their supplier's orders
            $orders = Order::where('supplier_id', $user->supplier_id)
                ->with(['customer'])
                ->latest()
                ->paginate(20);
        }
        
        return view('admin.orders.index', compact('orders'));
    }

    // public function updateStatus(Request $request, Order $order)
    // {
    //     // Check permission
    //     $this->authorize('update_order_status');
        
    //     // Check supplier access
    //     if (!auth()->user()->canManageSupplier($order->supplier_id)) {
    //         abort(403, 'Unauthorized');
    //     }
        
    //     $order->update([
    //         'order_status' => $request->status,
    //     ]);
        
    //     return redirect()->back()->with('success', 'Order status updated');
    // }


    // SUPPLIER ORDER INFORMATIONS
public function ordersinformations(Request $request)
{
    $user = auth()->user();
    
    // Start the base query
    $query = Order::with(['customer', 'supplier', 'orderItems.menuItem'])
        ->whereNotIn('order_status', ['delivered', 'cancelled']);

    // Check if user is NOT a super admin
    if (!$user->hasRole('super_admin')) {
        
        // 1. Check if the user is a direct owner of a supplier (your original logic)
        // 2. OR check if the user is linked to a supplier via the SupplierUser table
        $supplierId = Supplier::where('user_id', $user->id)->value('id') 
                      ?? SupplierUser::where('user_id', $user->id)->value('supplier_id');

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        } else {
            // If the user isn't an admin and isn't linked to any supplier, return empty
            $orders = collect(); 
            return view('in.order.orders.ordersinformations', compact('orders'));
        }
    }

    // Super admins fall through to here without the supplier filter
    $orders = $query->orderBy('created_at', 'desc')->get();

    return view('in.order.orders.ordersinformations', compact('orders'));
}
    public function completedOrders(Request $request)
    {
        $query = Order::with(['customer', 'supplier', 'orderItems.menuItem'])
            ->where('order_status', 'completed');

        // One line — handles owner, supplier_user member, and super_admin
        $this->scopeToSupplier($query);

        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('in.order.orders.completedOrders', compact('orders'));
    }

    public function pendingOrders(Request $request)
    {
        $query = Order::with(['customer', 'supplier', 'orderItems.menuItem'])
            ->where('order_status', 'pending');

        $this->scopeToSupplier($query);

        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('in.order.orders.pendingOrders', compact('orders'));
    }


public function cancelledOrders(Request $request)
{
    $supplier = Supplier::where('user_id', auth()->id())->first();

    $query = Order::with(['customer', 'supplier', 'orderItems.menuItem'])->where('order_status', 'cancelled');

    // If not super admin, filter by supplier
    if (!auth()->user()->hasRole('super_admin') && $supplier) {
        $query->where('supplier_id', $supplier->id);
    }

    $orders = $query->orderBy('created_at', 'desc')->get(); // get() instead of paginate()

    return view('in.order.orders.ordersinformations', compact('orders'));
}


    /**
     * Show the form for creating a new order
     */
    public function createordersinformations()
    {
        $suppliers = Supplier::all();
        $serviceTypes = DB::table('service_types')->get(); // Adjust based on your table
        $menuItems = MenuItem::where('is_active', true)->where('is_available', true)->get();

        return view('in.order.orders.createordersinformations', compact('suppliers', 'serviceTypes', 'menuItems'));
    }

    /**
     * Store a newly created order
     */
    public function storeordersinformations(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'order_type' => 'required|in:instant,scheduled,catering,subscription',
            'payment_method' => 'required|in:cash,card,mobile_money,wallet',
            'delivery_address_text' => 'required|string|max:500',
            'delivery_phone' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            // Add other validation rules as needed...
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                
                if (!$menuItem->is_available || !$menuItem->is_active) {
                    throw new \Exception("Menu item '{$menuItem->name}' is not available");
                }

                if ($menuItem->stock_quantity !== null && $menuItem->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for '{$menuItem->name}'");
                }

                $unitPrice = $menuItem->discounted_price ?? $menuItem->price;
                $addonsTotal = 0; // Logic for addons can be added here
                
                $itemSubtotal = ($unitPrice + $addonsTotal) * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'menu_item_id' => $item['menu_item_id'],
                    'item_name' => $menuItem->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                    'created_by' => Auth::id(),
                ];

                $menuItem->increment('order_count');
                if ($menuItem->stock_quantity !== null) {
                    $menuItem->decrement('stock_quantity', $item['quantity']);
                }
            }

            $taxAmount = ($subtotal) * 0.18; 
            $totalAmount = $subtotal + $taxAmount;

            $order = Order::create(array_merge($validated, [
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => Auth::id(),
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'delivery_otp' => rand(100000, 999999),
                'created_by' => Auth::id(),
                'status' => 'active',
            ]));

            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            DB::commit();

            return redirect()->route('ordersinformations', $order->id)
                             ->with('success', 'Order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function showordersinformations($id)
    {
        $order = Order::with([
            'orderItems.menuItem',
            'customer.customerProfile',
            'supplier',
            'deliveryAddress'
        ])->findOrFail($id);

        return view('in.order.orders.showordersinformations', compact('order'));
    }

    /**
     * Show the form for editing (Note: Usually limited to status or basic info)
     */
    public function editordersinformations($id)
    {
        $order = Order::findOrFail($id);
        
        if (!in_array($order->order_status, ['pending', 'accepted'])) {
            return redirect()->route('showordersinformations', $id)
                             ->with('error', 'This order cannot be edited anymore.');
        }

        return view('in.order.orders.editordersinformations', compact('order'));
    }

    /**
     * Update the specified order
     */
    public function updateordersinformations(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'delivery_address_text' => 'required|string|max:500',
            'delivery_phone' => 'required|string|max:20',
            'special_instructions' => 'nullable|string',
        ]);

        try {
            $order->update(array_merge($validated, ['updated_by' => Auth::id()]));
            return redirect()->route('showordersinformations', $id)->with('success', 'Order updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Update failed.');
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'order_status' => ['required', Rule::in(['pending', 'accepted', 'preparing', 'ready', 'dispatched', 'delivered', 'cancelled', 'rejected'])],
            'cancellation_reason' => 'required_if:order_status,cancelled',
        ]);

        try {
            DB::beginTransaction();
            
            $updateData = ['order_status' => $validated['order_status'], 'updated_by' => Auth::id()];

            if (in_array($validated['order_status'], ['cancelled', 'rejected'])) {
                $this->restoreStock($order);
            }

            $order->update($updateData);
            DB::commit();

            return back()->with('success', 'Status updated to ' . $validated['order_status']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Status update failed.');
        }
        }

        public function updateStatusConfirm(Request $request, $id){
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'order_status' => ['required', Rule::in(['pending', 'accepted', 'preparing', 'ready', 'dispatched', 'delivered', 'cancelled', 'rejected'])],
        ]);
        try {
            DB::beginTransaction();
            
            $updateData = [
                'order_status' => $validated['order_status'],
                 'updated_by' => Auth::id(),
                 'confirmed_at' => now(),
                 'accepted_at' => now()
                 ];

            $order->update($updateData);
            DB::commit();

            return back()->with('success', 'Status updated to ' . $validated['order_status']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Status update failed.');
        }

        }
        public function updateStatusPrepare(Request $request, $id){
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'order_status' => ['required', Rule::in(['pending', 'accepted', 'preparing', 'ready', 'dispatched', 'delivered', 'cancelled', 'rejected'])],
        ]);
        try {
            DB::beginTransaction();
            
            $updateData = [
                'order_status' => $validated['order_status'],
                 'updated_by' => Auth::id(),
                 'prepared_at' => now(),
                 'preparing_at' => now(),
                 ];

            $order->update($updateData);
            DB::commit();

            return back()->with('success', 'Status updated to ' . $validated['order_status']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Status update failed.');
        }

        }
        public function updateStatusReady(Request $request, $id){
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'order_status' => ['required', Rule::in(['pending', 'accepted', 'preparing', 'ready', 'dispatched', 'delivered', 'cancelled', 'rejected'])],
        ]);
        try {
            DB::beginTransaction();
            
            $updateData = [
                'order_status' => $validated['order_status'],
                 'updated_by' => Auth::id(),
                 'ready_at' => now(),
                 'dispatched_at' => now(),
                 ];

            $order->update($updateData);
            DB::commit();

            return back()->with('success', 'Status updated to ' . $validated['order_status']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Status update failed.');
        }
        }
        public function updateStatusDelivered(Request $request, $id){
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'order_status' => ['required', Rule::in(['pending', 'accepted', 'preparing', 'ready', 'dispatched', 'delivered', 'cancelled', 'rejected'])],
        ]);
        try {
            DB::beginTransaction();
            
            $updateData = [
                'order_status' => $validated['order_status'],
                 'updated_by' => Auth::id(),
                 'delivered_at' => now(),
                 ];

            $order->update($updateData);
            DB::commit();

            return back()->with('success', 'Status updated to ' . $validated['order_status']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Status update failed.');
        }

        }
        public function markOrderAsPaid(Request $request, $id){
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'payment_status' => ['required', Rule::in(['pending', 'paid'])],
        ]);
        try {
            DB::beginTransaction();
            
            $updateData = [
                'payment_status' => $validated['payment_status'],
                 'completed_at' => now(),
                 'payment_status' => 'paid',
                 ];

            $order->update($updateData);
            DB::commit();

            return back()->with('success', 'Status updated to ' . $validated['order_status']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Status update failed.');
        }

        }

    public function destroyordersinformations($id)
    {
        $order = Order::findOrFail($id);

        if ($order->order_status !== 'pending') {
            return back()->with('error', 'Only pending orders can be deleted.');
        }

        try {
            DB::beginTransaction();
            $this->restoreStock($order);
            $order->delete();
            DB::commit();

            return redirect()->route('ordersinformations')->with('success', 'Order deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Deletion failed.');
        }
    }

    /**
     * Private Helpers
     */
    private function generateOrderNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastOrder = Order::whereDate('created_at', Carbon::today())->orderBy('id', 'desc')->first();
        $sequence = $lastOrder ? (int) substr($lastOrder->order_number, -4) + 1 : 1;
        return 'ORD-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private function restoreStock(Order $order)
    {
        foreach ($order->orderItems as $item) {
            if ($item->menuItem && $item->menuItem->stock_quantity !== null) {
                $item->menuItem->increment('stock_quantity', $item->quantity);
                $item->menuItem->decrement('order_count');
            }
        }
    }

    public function ordersSummary(Request $request)
    {
    $supplier = Supplier::where('user_id', auth()->id())->first();

    $query = Order::with(['customer', 'supplier', 'orderItems.menuItem']);

    // If not super admin, filter by supplier
    if (!auth()->user()->hasRole('super_admin') && $supplier) {
        $query->where('supplier_id', $supplier->id);
    }
    
    $suppliers = $query->get('supplier_id');
    // Filtering
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

    $orders = $query->orderBy('created_at', 'desc')->get(); // get() instead of paginate()
    $average_order_value = (clone $query)->avg('total_amount');
            $statistics = [
            'total_orders' => $query->count(),
            'pending_orders' => (clone $query)->where('order_status', 'pending')->count(),
            'completed_orders' => (clone $query)->where('order_status', 'delivered')->count(),
            'cancelled_orders' => (clone $query)->where('order_status', 'cancelled')->count(),
            'total_revenue' => (clone $query)->where('payment_status', 'paid')->sum('total_amount'),
            'average_order_value' => (clone $query)->avg('total_amount'),
            'orders_by_status' => (clone $query)->groupBy('order_status')
                ->selectRaw('order_status, count(*) as count')
                ->pluck('count', 'order_status'),
            'orders_by_type' => (clone $query)->groupBy('order_type')
                ->selectRaw('order_type, count(*) as count')
                ->pluck('count', 'order_type'),
        ];

    $suppliers = $query->get('supplier_id');

    return view('in.order.orders.ordersSummary', compact('orders', 'statistics', 'suppliers'));
    }



}