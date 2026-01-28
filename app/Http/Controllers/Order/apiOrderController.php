<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Supplier;
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

        // Filter by customer
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by supplier
        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by order status
        if ($request->has('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        // Filter by order type
        if ($request->has('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by order number
        if ($request->has('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
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
            
            // Delivery information
            'delivery_address_id' => 'nullable|exists:customer_addresses,id',
            'delivery_address_text' => 'required|string|max:500',
            'delivery_latitude' => 'nullable|numeric|between:-90,90',
            'delivery_longitude' => 'nullable|numeric|between:-180,180',
            'delivery_phone' => 'required|string|max:20',
            'delivery_contact_name' => 'nullable|string|max:100',
            
            // Timing
            'scheduled_at' => 'nullable|date|after:now',
            'estimated_delivery_time' => 'nullable|integer|min:0',
            
            // Pricing
            'delivery_fee' => 'nullable|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'coupon_code' => 'nullable|string|max:50',
            
            // Instructions
            'special_instructions' => 'nullable|string',
            
            // Order items
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variant_id' => 'nullable|exists:menu_item_variants,id',
            'items.*.selected_addons' => 'nullable|array',
            'items.*.special_instructions' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Generate order number
            $orderNumber = $this->generateOrderNumber();

            // Calculate pricing
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                
                // Check if item is available
                if (!$menuItem->is_available || !$menuItem->is_active) {
                    throw new \Exception("Menu item '{$menuItem->name}' is not available");
                }

                // Check stock if applicable
                if ($menuItem->stock_quantity !== null && $menuItem->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for '{$menuItem->name}'");
                }

                $unitPrice = $menuItem->discounted_price ?? $menuItem->price;
                
                // Calculate addons total
                $addonsTotal = 0;
                if (isset($item['selected_addons'])) {
                    foreach ($item['selected_addons'] as $addon) {
                        $addonsTotal += ($addon['price'] ?? 0) * ($addon['quantity'] ?? 1);
                    }
                }

                $itemSubtotal = ($unitPrice + $addonsTotal) * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'menu_item_id' => $item['menu_item_id'],
                    'item_name' => $menuItem->name,
                    'item_description' => $menuItem->description,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'variant_id' => $item['variant_id'] ?? null,
                    'selected_addons' => isset($item['selected_addons']) ? json_encode($item['selected_addons']) : null,
                    'addons_total' => $addonsTotal,
                    'special_instructions' => $item['special_instructions'] ?? null,
                    'subtotal' => $itemSubtotal,
                    'created_by' => Auth::id(),
                    'status' => 'active',
                ];

                // Update menu item order count
                $menuItem->increment('order_count');
                
                // Decrement stock if applicable
                if ($menuItem->stock_quantity !== null) {
                    $menuItem->decrement('stock_quantity', $item['quantity']);
                }
            }

            // Calculate totals
            $deliveryFee = $validated['delivery_fee'] ?? 0;
            $serviceFee = $validated['service_fee'] ?? 0;
            $discountAmount = $validated['discount_amount'] ?? 0;
            $taxAmount = ($subtotal + $deliveryFee + $serviceFee) * 0.18; // 18% tax, adjust as needed
            $totalAmount = $subtotal + $deliveryFee + $serviceFee + $taxAmount - $discountAmount;

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => Auth::id(),
                'guest_session_id' => null,
                'supplier_id' => $validated['supplier_id'],
                'service_type_id' => $validated['service_type_id'],
                'order_type' => $validated['order_type'],
                'order_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'delivery_address_id' => $validated['delivery_address_id'] ?? null,
                'delivery_address_text' => $validated['delivery_address_text'],
                'delivery_latitude' => $validated['delivery_latitude'] ?? null,
                'delivery_longitude' => $validated['delivery_longitude'] ?? null,
                'delivery_phone' => $validated['delivery_phone'],
                'delivery_contact_name' => $validated['delivery_contact_name'] ?? null,
                'scheduled_at' => $validated['scheduled_at'] ?? null,
                'estimated_delivery_time' => $validated['estimated_delivery_time'] ?? null,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'service_fee' => $serviceFee,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'coupon_code' => $validated['coupon_code'] ?? null,
                'total_amount' => $totalAmount,
                'special_instructions' => $validated['special_instructions'] ?? null,
                'delivery_otp' => rand(100000, 999999),
                'created_by' => Auth::id(),
                'status' => 'active',
            ]);

            // Create order items
            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            DB::commit();

            // Load relationships
            $order->load(['orderItems.menuItem', 'customer', 'supplier']);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
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

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Only allow updates if order is in pending or accepted status
        if (!in_array($order->order_status, ['pending', 'accepted'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update order in current status'
            ], 403);
        }

        $validated = $request->validate([
            'delivery_address_text' => 'sometimes|string|max:500',
            'delivery_phone' => 'sometimes|string|max:20',
            'delivery_contact_name' => 'sometimes|string|max:100',
            'scheduled_at' => 'sometimes|nullable|date|after:now',
            'special_instructions' => 'sometimes|nullable|string',
        ]);

        try {
            $order->update(array_merge($validated, [
                'updated_by' => Auth::id()
            ]));

            $order->load(['orderItems.menuItem', 'customer', 'supplier']);

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'data' => $order
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'order_status' => [
                'required',
                Rule::in(['pending', 'accepted', 'preparing', 'ready', 'dispatched', 'delivered', 'cancelled', 'rejected', 'failed'])
            ],
            'cancellation_reason' => 'required_if:order_status,cancelled|string',
            'rejection_reason' => 'required_if:order_status,rejected|string',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'order_status' => $validated['order_status'],
                'updated_by' => Auth::id()
            ];

            // Update timestamps based on status
            switch ($validated['order_status']) {
                case 'accepted':
                    $updateData['accepted_at'] = now();
                    break;
                case 'preparing':
                    $updateData['prepared_at'] = now();
                    break;
                case 'dispatched':
                    $updateData['dispatched_at'] = now();
                    break;
                case 'delivered':
                    $updateData['delivered_at'] = now();
                    $updateData['payment_status'] = 'paid';
                    break;
                case 'cancelled':
                    $updateData['cancelled_at'] = now();
                    $updateData['cancellation_reason'] = $validated['cancellation_reason'];
                    // Restore stock
                    $this->restoreStock($order);
                    break;
                case 'rejected':
                    $updateData['rejection_reason'] = $validated['rejection_reason'];
                    // Restore stock
                    $this->restoreStock($order);
                    break;
            }

            $order->update($updateData);

            DB::commit();

            $order->load(['orderItems.menuItem', 'customer', 'supplier']);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'data' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_reference' => 'nullable|string|max:100',
        ]);

        $order->update([
            'payment_status' => $validated['payment_status'],
            'payment_reference' => $validated['payment_reference'] ?? $order->payment_reference,
            'updated_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully',
            'data' => $order
        ]);
    }

    /**
     * Soft delete the specified order
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Only allow deletion if order is in pending status
        if ($order->order_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete order that is not in pending status'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Restore stock
            $this->restoreStock($order);

            $order->update([
                'status' => 'deleted',
                'updated_by' => Auth::id()
            ]);
            $order->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order statistics
     */
    public function statistics(Request $request)
    {
        $query = Order::query();

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Filter by supplier
        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $stats = [
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

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastOrder = Order::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastOrder ? (int) substr($lastOrder->order_number, -4) + 1 : 1;
        
        return 'ORD-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Restore stock for cancelled/rejected orders
     */
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