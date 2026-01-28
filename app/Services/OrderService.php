<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderStatusChanged;

class OrderService
{
    /**
     * Create a new order with items
     */
    public function createOrder(array $data)
    {
        DB::beginTransaction();
        
        try {
            // Validate supplier
            $supplier = Supplier::findOrFail($data['supplier_id']);
            
            // Generate order number
            $orderNumber = $this->generateOrderNumber();
            
            // Calculate order totals
            $orderCalculation = $this->calculateOrderTotals($data['items']);
            
            if ($orderCalculation['error']) {
                throw new \Exception($orderCalculation['message']);
            }
            
            // Apply delivery and service fees
            $deliveryFee = $data['delivery_fee'] ?? $this->calculateDeliveryFee(
                $data['delivery_latitude'] ?? null,
                $data['delivery_longitude'] ?? null,
                $supplier
            );
            
            $serviceFee = $data['service_fee'] ?? $this->calculateServiceFee($orderCalculation['subtotal']);
            
            // Apply discount if coupon provided
            $discountAmount = 0;
            if (!empty($data['coupon_code'])) {
                $discountAmount = $this->applyCoupon($data['coupon_code'], $orderCalculation['subtotal']);
            }
            
            // Calculate tax
            $taxRate = config('app.tax_rate', 0.18); // 18% default
            $taxableAmount = $orderCalculation['subtotal'] + $deliveryFee + $serviceFee;
            $taxAmount = $taxableAmount * $taxRate;
            
            // Calculate total
            $totalAmount = $orderCalculation['subtotal'] + $deliveryFee + $serviceFee + $taxAmount - $discountAmount;
            
            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => Auth::id(),
                'supplier_id' => $data['supplier_id'],
                'service_type_id' => $data['service_type_id'],
                'order_type' => $data['order_type'],
                'order_status' => 'pending',
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_method'] === 'cash' ? 'pending' : 'pending',
                'delivery_address_id' => $data['delivery_address_id'] ?? null,
                'delivery_address_text' => $data['delivery_address_text'],
                'delivery_latitude' => $data['delivery_latitude'] ?? null,
                'delivery_longitude' => $data['delivery_longitude'] ?? null,
                'delivery_phone' => $data['delivery_phone'],
                'delivery_contact_name' => $data['delivery_contact_name'] ?? null,
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'estimated_delivery_time' => $data['estimated_delivery_time'] ?? $this->estimateDeliveryTime($supplier),
                'subtotal' => $orderCalculation['subtotal'],
                'delivery_fee' => $deliveryFee,
                'service_fee' => $serviceFee,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'coupon_code' => $data['coupon_code'] ?? null,
                'total_amount' => $totalAmount,
                'special_instructions' => $data['special_instructions'] ?? null,
                'delivery_otp' => $this->generateOTP(),
                'created_by' => Auth::id(),
                'status' => 'active',
            ]);
            
            // Create order items
            foreach ($orderCalculation['items'] as $itemData) {
                $order->orderItems()->create($itemData);
            }
            
            // Update menu items statistics
            $this->updateMenuItemsStatistics($orderCalculation['items']);
            
            DB::commit();
            
            // Send notifications
            $this->sendOrderCreatedNotifications($order);
            
            return [
                'success' => true,
                'order' => $order->load(['orderItems.menuItem', 'customer', 'supplier'])
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Calculate order totals from items
     */
    private function calculateOrderTotals(array $items)
    {
        $subtotal = 0;
        $orderItemsData = [];
        
        foreach ($items as $item) {
            try {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                
                // Validate availability
                if (!$menuItem->is_available || !$menuItem->is_active) {
                    return [
                        'error' => true,
                        'message' => "'{$menuItem->name}' is currently unavailable"
                    ];
                }
                
                // Validate stock
                if ($menuItem->stock_quantity !== null && $menuItem->stock_quantity < $item['quantity']) {
                    return [
                        'error' => true,
                        'message' => "Insufficient stock for '{$menuItem->name}'. Available: {$menuItem->stock_quantity}"
                    ];
                }
                
                // Get price
                $unitPrice = $menuItem->discounted_price ?? $menuItem->price;
                
                // Calculate addons
                $addonsTotal = 0;
                $selectedAddons = [];
                
                if (!empty($item['selected_addons'])) {
                    foreach ($item['selected_addons'] as $addon) {
                        $addonPrice = $addon['price'] ?? 0;
                        $addonQty = $addon['quantity'] ?? 1;
                        $addonsTotal += $addonPrice * $addonQty;
                        
                        $selectedAddons[] = [
                            'id' => $addon['id'],
                            'name' => $addon['name'],
                            'price' => $addonPrice,
                            'quantity' => $addonQty
                        ];
                    }
                }
                
                // Calculate item subtotal
                $itemSubtotal = ($unitPrice + $addonsTotal) * $item['quantity'];
                $subtotal += $itemSubtotal;
                
                // Prepare item data
                $orderItemsData[] = [
                    'menu_item_id' => $item['menu_item_id'],
                    'item_name' => $menuItem->name,
                    'item_description' => $menuItem->description,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'variant_id' => $item['variant_id'] ?? null,
                    'selected_addons' => !empty($selectedAddons) ? json_encode($selectedAddons) : null,
                    'addons_total' => $addonsTotal,
                    'special_instructions' => $item['special_instructions'] ?? null,
                    'subtotal' => $itemSubtotal,
                    'created_by' => Auth::id(),
                    'status' => 'active',
                ];
                
            } catch (\Exception $e) {
                return [
                    'error' => true,
                    'message' => "Error processing menu item: " . $e->getMessage()
                ];
            }
        }
        
        return [
            'error' => false,
            'subtotal' => $subtotal,
            'items' => $orderItemsData
        ];
    }
    
    /**
     * Update order status with workflow validation
     */
    public function updateOrderStatus(Order $order, string $newStatus, array $additionalData = [])
    {
        // Validate status transition
        if (!$this->isValidStatusTransition($order->order_status, $newStatus)) {
            return [
                'success' => false,
                'message' => "Cannot change status from {$order->order_status} to {$newStatus}"
            ];
        }
        
        DB::beginTransaction();
        
        try {
            $updateData = [
                'order_status' => $newStatus,
                'updated_by' => Auth::id()
            ];
            
            // Handle status-specific updates
            switch ($newStatus) {
                case 'accepted':
                    $updateData['accepted_at'] = now();
                    break;
                    
                case 'preparing':
                    $updateData['prepared_at'] = now();
                    break;
                    
                case 'ready':
                    // No specific timestamp
                    break;
                    
                case 'dispatched':
                    $updateData['dispatched_at'] = now();
                    break;
                    
                case 'delivered':
                    $updateData['delivered_at'] = now();
                    $updateData['payment_status'] = 'paid';
                    $updateData['delivery_notes'] = $additionalData['delivery_notes'] ?? null;
                    $updateData['delivery_photo'] = $additionalData['delivery_photo'] ?? null;
                    break;
                    
                case 'cancelled':
                    $updateData['cancelled_at'] = now();
                    $updateData['cancellation_reason'] = $additionalData['cancellation_reason'] ?? 'No reason provided';
                    $this->restoreOrderStock($order);
                    break;
                    
                case 'rejected':
                    $updateData['rejection_reason'] = $additionalData['rejection_reason'] ?? 'No reason provided';
                    $this->restoreOrderStock($order);
                    break;
                    
                case 'failed':
                    $this->restoreOrderStock($order);
                    break;
            }
            
            $order->update($updateData);
            
            DB::commit();
            
            // Send notifications
            $this->sendOrderStatusNotification($order, $newStatus);
            
            return [
                'success' => true,
                'order' => $order->fresh(['orderItems.menuItem', 'customer', 'supplier'])
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Validate status transition
     */
    private function isValidStatusTransition(string $currentStatus, string $newStatus): bool
    {
        $allowedTransitions = [
            'pending' => ['accepted', 'rejected', 'cancelled'],
            'accepted' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['dispatched', 'cancelled'],
            'dispatched' => ['delivered', 'failed'],
            'delivered' => [], // Terminal state
            'cancelled' => [], // Terminal state
            'rejected' => [],  // Terminal state
            'failed' => [],    // Terminal state
        ];
        
        return in_array($newStatus, $allowedTransitions[$currentStatus] ?? []);
    }
    
    /**
     * Restore stock for cancelled/rejected orders
     */
    private function restoreOrderStock(Order $order)
    {
        foreach ($order->orderItems as $item) {
            if ($item->menuItem) {
                if ($item->menuItem->stock_quantity !== null) {
                    $item->menuItem->increment('stock_quantity', $item->quantity);
                }
                $item->menuItem->decrement('order_count');
            }
        }
    }
    
    /**
     * Update menu items statistics
     */
    private function updateMenuItemsStatistics(array $items)
    {
        foreach ($items as $itemData) {
            $menuItem = MenuItem::find($itemData['menu_item_id']);
            if ($menuItem) {
                $menuItem->increment('order_count');
                $menuItem->increment('view_count');
                
                if ($menuItem->stock_quantity !== null) {
                    $menuItem->decrement('stock_quantity', $itemData['quantity']);
                }
            }
        }
    }
    
    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        $date = Carbon::now()->format('Ymd');
        $lastOrder = Order::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastOrder ? (int) substr($lastOrder->order_number, -4) + 1 : 1;
        
        return 'ORD-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Generate OTP for delivery verification
     */
    private function generateOTP(): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Calculate delivery fee based on distance
     */
    private function calculateDeliveryFee($latitude, $longitude, Supplier $supplier): float
    {
        if (!$latitude || !$longitude) {
            return 0.00;
        }
        
        // Calculate distance (simplified - use proper geolocation service in production)
        $distance = $this->calculateDistance(
            $supplier->latitude ?? 0,
            $supplier->longitude ?? 0,
            $latitude,
            $longitude
        );
        
        // Base fee + per km charge
        $baseFee = config('app.delivery_base_fee', 5.00);
        $perKmRate = config('app.delivery_per_km_rate', 2.00);
        
        return $baseFee + ($distance * $perKmRate);
    }
    
    /**
     * Calculate service fee
     */
    private function calculateServiceFee(float $subtotal): float
    {
        $serviceFeePer = config('app.service_fee_percentage', 0.10); // 10%
        return $subtotal * $serviceFeePer;
    }
    
    /**
     * Apply coupon code
     */
    private function applyCoupon(string $couponCode, float $subtotal): float
    {
        // Implement coupon logic here
        // This is a placeholder
        return 0.00;
    }
    
    /**
     * Estimate delivery time
     */
    private function estimateDeliveryTime(Supplier $supplier): int
    {
        // Return estimated time in minutes
        return $supplier->average_delivery_time ?? 45;
    }
    
    /**
     * Calculate distance between two points
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
    
    /**
     * Send order created notifications
     */
    private function sendOrderCreatedNotifications(Order $order)
    {
        // Notify customer
        // Notify supplier
        // Add your notification logic here
    }
    
    /**
     * Send order status notification
     */
    private function sendOrderStatusNotification(Order $order, string $newStatus)
    {
        // Send appropriate notifications based on status
        // Add your notification logic here
    }
    
    /**
     * Get order analytics
     */
    public function getOrderAnalytics(array $filters = [])
    {
        $query = Order::query();
        
        if (isset($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }
        
        if (isset($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }
        
        return [
            'total_orders' => $query->count(),
            'total_revenue' => $query->where('payment_status', 'paid')->sum('total_amount'),
            'average_order_value' => $query->avg('total_amount'),
            'completed_orders' => (clone $query)->where('order_status', 'delivered')->count(),
            'cancelled_orders' => (clone $query)->where('order_status', 'cancelled')->count(),
            'pending_orders' => (clone $query)->where('order_status', 'pending')->count(),
            'orders_by_type' => (clone $query)->groupBy('order_type')
                ->selectRaw('order_type, count(*) as count')
                ->pluck('count', 'order_type'),
            'revenue_by_day' => (clone $query)
                ->where('payment_status', 'paid')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
                ->get(),
        ];
    }
}