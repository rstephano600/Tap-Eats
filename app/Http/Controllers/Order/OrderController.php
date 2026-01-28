<?php
namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Supplier;
use App\Models\ServiceType; // Ensure this model exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'supplier', 'orderItems.menuItem']);

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

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate($request->get('per_page', 15));

        return view('in.order.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $serviceTypes = DB::table('service_types')->get(); // Adjust based on your table
        $menuItems = MenuItem::where('is_active', true)->where('is_available', true)->get();

        return view('in.order.orders.create', compact('suppliers', 'serviceTypes', 'menuItems'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
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

            return redirect()->route('orders.show', $order->id)
                             ->with('success', 'Order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Order::with([
            'orderItems.menuItem',
            'customer.customerProfile',
            'supplier',
            'deliveryAddress'
        ])->findOrFail($id);

        return view('in.order.orders.show', compact('order'));
    }

    /**
     * Show the form for editing (Note: Usually limited to status or basic info)
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        
        if (!in_array($order->order_status, ['pending', 'accepted'])) {
            return redirect()->route('orders.show', $id)
                             ->with('error', 'This order cannot be edited anymore.');
        }

        return view('in.order.orders.edit', compact('order'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'delivery_address_text' => 'required|string|max:500',
            'delivery_phone' => 'required|string|max:20',
            'special_instructions' => 'nullable|string',
        ]);

        try {
            $order->update(array_merge($validated, ['updated_by' => Auth::id()]));
            return redirect()->route('orders.show', $id)->with('success', 'Order updated.');
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

    /**
     * Soft delete/Cancel order
     */
    public function destroy($id)
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

            return redirect()->route('orders.index')->with('success', 'Order deleted.');
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
}