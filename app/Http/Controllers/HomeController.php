<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierLocation;
use App\Models\CustomerProfile;
use App\Models\BusinessType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceType;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\CustomerAddress;
use App\Models\GuestSession;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class HomeController extends Controller
{

    public function homepage()
    {
    $supplierCount = Supplier::where('status', 'Active')
        ->where('verification_status', 'verified')
        ->count();
        
    $customerProfile = CustomerProfile::count();

    $categories = MenuCategory::withCount(['menuItems as suppliers_count' => function($query) {
        $query->select(DB::raw('count(distinct(supplier_id))'))
              ->whereHas('supplier', function($s) {
                  $s->where('status', 'Active')
                    ->where('verification_status', 'verified');
              });
    }])
    ->inRandomOrder() 
    ->take(6)
    ->get();

    $featuredSuppliers = Supplier::where('status', 'Active')
        ->where('verification_status', 'verified')
        ->inRandomOrder()
        ->take(4)
        ->get();

    return view('out.home.home2', compact('supplierCount', 'customerProfile', 'categories','featuredSuppliers'));
    }
    public function searchsupplierlocation(Request $request)
    {
        // 1. Get the search terms from the URL
        $locationSearch = $request->input('location');
        $querySearch = $request->input('query');

        // 2. Build the query
        $results = SupplierLocation::query()
            ->with('supplier') // Eager load the supplier to avoid N+1 issues
            ->where('is_active', true)
            ->when($locationSearch, function ($query, $location) {
                $query->where(function($q) use ($location) {
                    $q->where('location_name', 'LIKE', "%{$location}%")
                      ->orWhere('city', 'LIKE', "%{$location}%")
                      ->orWhere('address_line1', 'LIKE', "%{$location}%")
                      ->orWhere('postal_code', 'LIKE', "%{$location}%");
                });
            })
            ->when($querySearch, function ($query, $term) {
                // If you want to search by supplier name or other details
                $query->whereHas('supplier', function($q) use ($term) {
                    $q->where('business_name', 'LIKE', "%{$term}%");
                })->orWhere('location_name', 'LIKE', "%{$term}%");
            })
            ->get();

        // 3. Return to the search results view
        return view('out.home.search_results', compact('results'));
    }

    public function restaurantspublic()
    {
        $suppliers = Supplier::where('is_active', true)->where('status', 'Active')->where('verification_status', 'verified')
            ->with('foods')
            ->get();

        return view('out.home.restaurantspublic', compact('suppliers'));
    }

public function restaurantsshow($encryptedId)
{
    try {
        // 1. Decrypt the ID
        $id = decrypt($encryptedId);
        
        // 2. Fetch the supplier with menu items, grouped by category
        // We use eager loading to prevent N+1 query issues
        $supplier = Supplier::with(['menuItems' => function($query) {
            $query->where('is_available', true); // Optional: only show available items
        }, 'menuItems.category']) // Assuming relationship is named 'category' in MenuItem model
        ->findOrFail($id);

        // 3. Group the menu items by category name for easier display in Blade
        $menuByCategory = $supplier->menuItems->groupBy(function($item) {
            return $item->category ? $item->category->name : 'General';
        });

        return view('out.home.restaurantsshow', compact('supplier', 'menuByCategory'));

    } catch (DecryptException $e) {
        // If someone messes with the URL string, send them back
        return redirect()->route('restaurantspublic')->with('error', 'Invalid restaurant link.');
    }
}

public function restaurantsshowok(Request $request, $id)
{
    $supplierId = decrypt($id);

    $supplier = Supplier::where('id', $supplierId)
        ->where('status', 'active')
        ->where('verification_status', 'verified')
        ->firstOrFail();
$supplier->load([
    'menuCategories' => function ($q) use ($request, $supplierId) {
        $q->where('is_active', true)
          ->whereHas('menuItems', function ($q) use ($supplierId) {
              $q->where(function ($q) use ($supplierId) {
                    $q->where('supplier_id', $supplierId)
                      ->orWhereNull('supplier_id');
              })
              ->whereNull('deleted_at');
          })
          ->when($request->category_id, fn ($q) =>
                $q->where('id', $request->category_id)
          )
          ->orderBy('display_order');
    },

    'menuCategories.menuItems' => function ($q) use ($supplierId) {
        $q->where(function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId)
                  ->orWhereNull('supplier_id');
        })
        ->whereNull('deleted_at')
        ->orderBy('display_order');
    }
]);

    return view('out.home.restaurantsshow', compact('supplier'));
}

public function addToCart(Request $request)
{
    $item = MenuItem::findOrFail($request->item_id);
    
    // Store the supplier ID in the session so the checkout knows which restaurant we are at
    session()->put('cart_supplier_id', $item->supplier_id);

    $cart = session()->get('cart', []);
    $cartId = $item->id; 

    if(isset($cart[$cartId])) {
        $cart[$cartId]['quantity']++;
    } else {
        $cart[$cartId] = [
            "id" => $item->id, // Add ID here so JS can use it
            "name" => $item->name,
            "quantity" => 1,
            "price" => $item->discounted_price ?? $item->price,
            "image" => $item->main_image_url,
            "supplier_id" => $item->supplier_id // Store supplier per item
        ];
    }
        session()->put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'cart_count' => count($cart),
            'message' => $item->name . ' added to cart!'
        ]);
    }
public function checkoutindex(Request $request)
{
    // Get cart from session (already synced by cart.sync route)
    $cart = session()->get('cart', []);
    
    if (empty($cart)) {
        return redirect()->route('home')->with('error', 'Your cart is empty');
    }

    // Group cart items by supplier_id
    $cartBySupplier = collect($cart)->groupBy('supplier_id');
    
    // Get all unique supplier IDs
    $supplierIds = $cartBySupplier->keys()->toArray();
    
    // Fetch all suppliers involved in this cart
    $suppliers = Supplier::whereIn('id', $supplierIds)
        ->where('status', 'active')
        ->get()
        ->keyBy('id');
    
    // Verify all suppliers exist and are active
    if ($suppliers->count() !== count($supplierIds)) {
        return redirect()->route('home')->with('error', 'One or more suppliers are no longer available');
    }
    
    // IMPORTANT FIX: Always set $supplier to the first one (or primary supplier)
    // This prevents the null error in the view
    $isSingleSupplier = count($supplierIds) === 1;
    $supplier = $suppliers->first(); // Always get at least one supplier

    // Get service types
    $serviceTypes = ServiceType::where('status', 'active')
        ->orderBy('display_order')
        ->get();

    // Get saved addresses for authenticated users
    $savedAddresses = Auth::check() 
        ? CustomerAddress::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get() 
        : collect();

    // Calculate totals for each supplier
    $orderSummary = [];
    foreach ($cartBySupplier as $supplierId => $items) {
        $subtotal = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        $deliveryFee = 0.00;
        $serviceFee = 0.00;
        $tax = $subtotal * 0.00;
        $total = $subtotal + $deliveryFee + $serviceFee + $tax;
        
        $orderSummary[$supplierId] = [
            'supplier' => $suppliers->get($supplierId),
            'items' => $items,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'service_fee' => $serviceFee,
            'tax' => $tax,
            'total' => $total,
        ];
    }

    return view('out.home.checkoutindex', compact(
        'supplier',
        'suppliers', 
        'serviceTypes', 
        'savedAddresses', 
        'cart',
        'orderSummary',
        'isSingleSupplier'
    ));
}
    // In your controller
public function syncCart(Request $request)
{
    $cart = json_decode($request->input('cart_data'), true);
    session()->put('cart', $cart);
    return response()->json(['success' => true]);
}



public function checkoutprocess(Request $request)
{
    // 1. First, get service type to determine if delivery fields are required
    $serviceType = ServiceType::find($request->service_type_id);
    $isDelivery = $serviceType && stripos($serviceType->name, 'delivery') !== false;

    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_email' => 'required|email|max:255',
        'customer_phone' => 'required|string|max:20',
        'service_type_id' => 'required|exists:service_types,id',
        'order_type' => 'required|in:instant,scheduled', // Fixed to match your JS
        'scheduled_at' => 'required_if:order_type,scheduled|nullable|date|after:now',
        'payment_method' => 'required|in:cash,card,mobile_money',
        
        // Delivery fields - required only for delivery service
        'delivery_address' => $isDelivery ? 'required|string' : 'nullable|string',
        'delivery_city' => $isDelivery ? 'required|string' : 'nullable|string',
        'delivery_postal_code' => 'nullable|string',
        'delivery_latitude' => 'nullable|numeric',
        'delivery_longitude' => 'nullable|numeric',
        'special_instructions' => 'nullable|string|max:500',
        
        // Cart data
        'cart_items' => 'required|json',
        
        // Optional: If you want to validate the calculated totals
        'subtotal' => 'required|numeric|min:0',
        'delivery_fee' => 'required|numeric|min:0',
        'service_fee' => 'required|numeric|min:0',
        'tax_amount' => 'required|numeric|min:0',
        'total_amount' => 'required|numeric|min:0',
    ]);

    try {
        DB::beginTransaction();

        $cartItems = json_decode($validated['cart_items'], true);
        if (empty($cartItems)) {
            throw new \Exception('Cart is empty');
        }

        // SECURITY: Verify menu items exist and validate prices
        $menuItemIds = collect($cartItems)->pluck('id')->unique()->toArray();
        $menuItems = MenuItem::whereIn('id', $menuItemIds)
            ->where('status', 'active')
            ->get()
            ->keyBy('id');

        // Validate all items exist
        foreach ($cartItems as $item) {
            if (!isset($menuItems[$item['id']])) {
                throw new \Exception("Menu item {$item['name']} is no longer available");
            }
            
            // Validate price hasn't changed (allow small floating point difference)
            $dbPrice = $menuItems[$item['id']]->discounted_price ?? $menuItems[$item['id']]->price;
            if (abs($dbPrice - $item['price']) > 0.01) {
                throw new \Exception("Price for {$item['name']} has changed. Please refresh your cart.");
            }
        }

        // Group items by supplier to create one order per restaurant
        $groupedItems = collect($cartItems)->groupBy('supplier_id');
        $ordersCreated = [];

        // Determine Customer/Guest Info once
        $customerId = Auth::id();
        $guestSessionId = null;
        
        if (!$customerId) {
            $guestSession = GuestSession::create([
                'session_id' => Str::uuid(), // Already returns a UUID string
                'email' => $validated['customer_email'],
                'phone' => $validated['customer_phone'],
                'name' => $validated['customer_name'],
                // Add other required fields
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'last_activity_at' => now(),
                'expires_at' => now()->addDays(30),
            ]);
    
            $guestSessionId = $guestSession->id;
            session(['guest_session_id' => $guestSessionId]);
        }

        // Create one order per supplier
        foreach ($groupedItems as $supplierId => $items) {
            // SECURITY: Recalculate totals server-side (NEVER trust client-side totals)
            $subtotal = $items->sum(function($item) use ($menuItems) {
                $dbPrice = $menuItems[$item['id']]->discounted_price ?? $menuItems[$item['id']]->price;
                return $dbPrice * $item['quantity'];
            });

            // Get fees from config or database (hardcoded for now)
            $taxRate = 0.0; // 10% - should come from settings
            $deliveryFee = $isDelivery ? 1.00 : 0.00;
            $serviceFee = 0.00;
            
            $taxAmount = ($subtotal + $deliveryFee + $serviceFee) * $taxRate;
            $totalAmount = $subtotal + $deliveryFee + $serviceFee + $taxAmount;

            // Verify supplier exists and is active
            $supplier = Supplier::where('id', $supplierId)
                ->where('status', 'active')
                ->first();
                
            if (!$supplier) {
                throw new \Exception("Restaurant is no longer available");
            }

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $customerId,
                'guest_session_id' => $guestSessionId,
                'supplier_id' => $supplierId,
                'service_type_id' => $validated['service_type_id'],
                'order_type' => $validated['order_type'],
                'scheduled_at' => $validated['scheduled_at'] ?? now(),
                'order_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                
                // Delivery information
                'delivery_address_text' => $validated['delivery_address'] ?? null,
                'delivery_city' => $validated['delivery_city'] ?? null,
                'delivery_postal_code' => $validated['delivery_postal_code'] ?? null,
                'delivery_latitude' => $validated['delivery_latitude'] ?? null,
                'delivery_longitude' => $validated['delivery_longitude'] ?? null,
                'delivery_phone' => $validated['customer_phone'],
                'delivery_contact_name' => $validated['customer_name'],
                'special_instructions' => $validated['special_instructions'] ?? null,
                
                // Financial data
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'delivery_fee' => $deliveryFee,
                'service_fee' => $serviceFee,
                'total_amount' => $totalAmount,
                
                'status' => 'active',
                'created_by' => $customerId,
            ]);

            // Create order items
            foreach ($items as $item) {
                $menuItem = $menuItems[$item['id']];
                $actualPrice = $menuItem->discounted_price ?? $menuItem->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['id'],
                    'item_name' => $menuItem->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $actualPrice,
                    'subtotal' => $actualPrice * $item['quantity'],
                    'status' => 'active',
                ]);
            }
            
            $ordersCreated[] = $order;
        }

        DB::commit();

        // Clear server-side cart
        session()->forget(['cart', 'cart_supplier_id']);

        // For multi-order, redirect to a summary page or first order
        if (count($ordersCreated) === 1) {
            return redirect()
                ->route('orderconfirmation', ['orderNumber' => $ordersCreated[0]->order_number])
                ->with('success', 'Order placed successfully!')
                ->with('clear_cart', true); // Signal to clear localStorage
        } else {
            // Multiple orders - show all order numbers
            $orderNumbers = collect($ordersCreated)->pluck('order_number')->toArray();
            return redirect()
                ->route('orderconfirmation', ['orderNumber' => $ordersCreated[0]->order_number])
                ->with('success', 'Orders placed successfully!')
                ->with('all_orders', $orderNumbers)
                ->with('clear_cart', true);
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return back()->withErrors($e->validator)->withInput();
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Checkout Error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'user' => Auth::id(),
        ]);
        
        return back()
            ->withInput()
            ->with('error', 'Failed to process order: ' . $e->getMessage());
    }
}
/**
 * Display all orders for the current user/guest
 */
public function ordersindex()
{
    $orders = collect();

    if (Auth::check()) {
        // Get orders for logged-in customer
        $orders = Order::where('customer_id', Auth::id())
            ->with(['supplier', 'serviceType', 'orderItems.menuItem'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    } else {
        // Get orders for guest using session
        $guestSessionId = session('guest_session_id');
        
        if ($guestSessionId) {
            $orders = Order::where('guest_session_id', $guestSessionId)
                ->with(['supplier', 'serviceType', 'orderItems.menuItem'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
    }

    return view('out.home.ordersindex', compact('orders'));
}

/**
 * Track a specific order
 */
public function orderstrack($orderNumber)
{
    $order = Order::where('order_number', $orderNumber)
        ->with(['supplier', 'serviceType', 'orderItems.menuItem', 'driver'])
        ->firstOrFail();

    // Security: Verify user has access to this order
    if (Auth::check()) {
        if ($order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order');
        }
    } else {
        $guestSessionId = session('guest_session_id');
        if ($order->guest_session_id !== $guestSessionId) {
            abort(403, 'Unauthorized access to this order');
        }
    }

    // Get order status timeline
    $statusTimeline = $this->getOrderStatusTimeline($order);

    return view('out.home.orderstrack', compact('order', 'statusTimeline'));
    }

/**
 * Helper: Get order status timeline
 */
private function getOrderStatusTimeline($order)
{
    $timeline = [
        [
            'status' => 'pending',
            'label' => 'Order Placed',
            'icon' => 'bi-cart-check',
            'completed' => true,
            'time' => $order->created_at,
        ],
        [
            'status' => 'confirmed',
            'label' => 'Confirmed by Restaurant',
            'icon' => 'bi-check-circle',
            'completed' => in_array($order->order_status, ['confirmed', 'accepted', 'preparing', 'ready', 'dispatched', 'delivered']),
            'time' => $order->confirmed_at ?? null,
        ],
        [
            'status' => 'preparing',
            'label' => 'Being Prepared',
            'icon' => 'bi-fire',
            'completed' => in_array($order->order_status, ['preparing', 'ready', 'dispatched', 'delivered']),
            'time' => $order->preparing_at ?? null,
        ],
    ];

    // Add delivery/pickup specific steps
    if ($order->serviceType && stripos($order->serviceType->name, 'delivery') !== false) {
        $timeline[] = [
            'status' => 'ready',
            'label' => 'Ready for Pickup by Driver',
            'icon' => 'bi-box-seam',
            'completed' => in_array($order->order_status, ['ready', 'dispatched', 'delivered']),
            'time' => $order->ready_at ?? null,
        ];
        $timeline[] = [
            'status' => 'dispatched',
            'label' => 'Out for Delivery',
            'icon' => 'bi-truck',
            'completed' => in_array($order->order_status, ['dispatched', 'delivered']),
            'time' => $order->dispatched_at ?? null,
        ];
        $timeline[] = [
            'status' => 'delivered',
            'label' => 'Delivered',
            'icon' => 'bi-check-circle-fill',
            'completed' => $order->order_status === 'delivered',
            'time' => $order->delivered_at ?? null,
        ];
    } else {
        $timeline[] = [
            'status' => 'ready',
            'label' => 'Ready for Pickup',
            'icon' => 'bi-box-seam',
            'completed' => in_array($order->order_status, ['ready', 'completed', 'dispatched', 'delivered']),
            'time' => $order->ready_at ?? null,
        ];
        $timeline[] = [
            'status' => 'delivered',
            'label' => 'Picked Up',
            'icon' => 'bi-check-circle-fill',
            'completed' => $order->order_status === 'delivered',
            'time' => $order->completed_at ?? null,
        ];
    }

    return $timeline;
   }

    /**
     * Show order confirmation page
     */
    public function orderconfirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['orderItems.menuItem', 'supplier', 'serviceType'])
            ->firstOrFail();

        // Check authorization
        if (Auth::check()) {
            if ($order->customer_id !== Auth::id()) {
                abort(403, 'Unauthorized');
            }
        } else {
            // For guest orders, verify session or allow within time frame
            if ($order->created_at->diffInHours(now()) > 1) {
                abort(403, 'Order confirmation expired');
            }
        }

        return view('out.home.orderconfirmation', compact('order'));
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . strtoupper(Str::random(3)) . '-' . date('Ymd') . '-' . rand(1000, 9999);
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Calculate delivery fee based on distance
     */
    private function calculateDeliveryFee($distance)
    {
        // Base delivery fee
        $baseFee = 1.00;
        
        // Additional fee per km (after first 5km)
        $perKmFee = 1.00;
        
        if ($distance <= 5) {
            return $baseFee;
        }
        
        $additionalDistance = $distance - 0;
        return $baseFee + ($additionalDistance * $perKmFee);
    }

    /**
     * Send order notifications
     */
    private function sendOrderNotifications(Order $order)
    {
        // Implement email/SMS notifications
        // Mail::to($order->customer->email)->send(new OrderPlacedMail($order));
        // SMS to supplier
        // SMS to customer
    }


/**
 * Show customer support page
 */
public function customersupport()
{
    // Get user's recent tickets
    $recentTickets = collect();
    
    if (Auth::check()) {
        $recentTickets = SupportTicket::where('customer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    } else {
        $guestSessionId = session('guest_session_id');
        if ($guestSessionId) {
            $recentTickets = SupportTicket::where('guest_session_id', $guestSessionId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
    }

    // Get user's recent orders for quick reference
    $recentOrders = collect();
    if (Auth::check()) {
        $recentOrders = Order::where('customer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'order_number', 'created_at', 'total_amount']);
    } else {
        $guestSessionId = session('guest_session_id');
        if ($guestSessionId) {
            $recentOrders = Order::where('guest_session_id', $guestSessionId)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(['id', 'order_number', 'created_at', 'total_amount']);
        }
    }

    return view('out.home.customersupport', compact('recentTickets', 'recentOrders'));
}

/**
 * Submit support ticket
 */
public function submitSupportTicket(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_email' => 'required|email|max:255',
        'customer_phone' => 'nullable|string|max:20',
        'order_number' => 'nullable|string|exists:orders,order_number',
        'category' => 'required|in:order_issue,delivery_issue,payment_issue,food_quality,missing_items,wrong_order,refund_request,account_issue,general_inquiry,other',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:10',
        'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
    ]);

    try {
        DB::beginTransaction();

        // Get order if provided
        $order = null;
        if ($validated['order_number']) {
            $order = Order::where('order_number', $validated['order_number'])->first();
        }

        // Determine priority based on category
        $priority = match($validated['category']) {
            'payment_issue', 'wrong_order', 'missing_items' => 'high',
            'delivery_issue', 'food_quality', 'refund_request' => 'medium',
            default => 'low'
        };

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support-attachments', 'public');
                $attachments[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
        }

        // Create ticket
        $ticket = SupportTicket::create([
            'ticket_number' => $this->generateTicketNumber(),
            'customer_id' => Auth::id(),
            'guest_session_id' => Auth::check() ? null : session('guest_session_id'),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'] ?? null,
            'order_id' => $order?->id,
            'order_number' => $validated['order_number'] ?? null,
            'category' => $validated['category'],
            'priority' => $priority,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'attachments' => $attachments,
            'status' => 'open',
            'created_by' => Auth::id(),
        ]);

        DB::commit();

        // TODO: Send email notification to support team

        return redirect()
            ->route('customersupport')
            ->with('success', 'Support ticket created successfully! Ticket #' . $ticket->ticket_number);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Support Ticket Error: ' . $e->getMessage());
        
        return back()
            ->withInput()
            ->with('error', 'Failed to create support ticket. Please try again.');
    }
}

/**
 * Generate unique ticket number
 */
private function generateTicketNumber()
{
    do {
        $ticketNumber = 'TKT-' . strtoupper(Str::random(8));
    } while (SupportTicket::where('ticket_number', $ticketNumber)->exists());
    
    return $ticketNumber;
}

/**
 * Show FAQ page
 */
public function faq()
{
    $faqs = [
        'Orders' => [
            [
                'question' => 'How can I track my order?',
                'answer' => 'You can track your order by going to "My Orders" and clicking on the specific order. You\'ll see real-time updates on your order status.'
            ],
            [
                'question' => 'Can I cancel my order?',
                'answer' => 'Yes, you can cancel your order before it\'s confirmed by the restaurant. Go to your order details and click "Cancel Order".'
            ],
            [
                'question' => 'How long does delivery take?',
                'answer' => 'Delivery typically takes 30-45 minutes depending on your location and restaurant preparation time. You\'ll see an estimated time when placing your order.'
            ],
        ],
        'Payments' => [
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept Cash on Delivery, Credit/Debit Cards, and Mobile Money (M-Pesa, Airtel Money, etc.).'
            ],
            [
                'question' => 'Is it safe to pay online?',
                'answer' => 'Yes, all online payments are processed through secure, encrypted payment gateways. We never store your card details.'
            ],
            [
                'question' => 'Can I get a refund?',
                'answer' => 'Refunds are processed on a case-by-case basis. Contact our support team with your order details for assistance.'
            ],
        ],
        'Account' => [
            [
                'question' => 'Do I need an account to order?',
                'answer' => 'No, you can order as a guest. However, creating an account lets you save addresses, track orders, and get exclusive offers.'
            ],
            [
                'question' => 'How do I reset my password?',
                'answer' => 'Click "Forgot Password" on the login page and follow the instructions sent to your email.'
            ],
        ],
        'Delivery' => [
            [
                'question' => 'What areas do you deliver to?',
                'answer' => 'We deliver to most areas within the city. Enter your address at checkout to see if we deliver to your location.'
            ],
            [
                'question' => 'How much is the delivery fee?',
                'answer' => 'Delivery fees vary by restaurant and distance, typically ranging from $3-$7. You\'ll see the exact fee before placing your order.'
            ],
        ],
    ];

    return view('out.home.faq', compact('faqs'));
}


/**
 * Display daily meal items grouped by categories with random ordering
 */

public function dailymenuitems(Request $request)
{
    // Get search query
    $search = $request->input('search');
    
    // Get filter parameters
    $mealType = $request->input('meal_type'); // breakfast, lunch, dinner
    $dietaryFilter = $request->input('dietary'); // vegetarian, vegan, gluten_free, halal
    $priceRange = $request->input('price_range'); // under_10, 10_20, 20_30, above_30
    
    // Base query for available menu items
    $query = MenuItem::with(['menuCategory', 'supplier'])
        ->where('is_available', true)
        ->inStock();
    
    // Apply search filter
    if ($search) {
        $query->search($search);
    }
    
    // Apply meal type filter (based on category or available_times)
    if ($mealType) {
        $query->whereHas('menuCategory', function($q) use ($mealType) {
            $q->where('name', 'like', "%{$mealType}%");
        });
    }
    
    // Apply dietary filters
    if ($dietaryFilter) {
        switch ($dietaryFilter) {
            case 'vegetarian':
                $query->where('is_vegetarian', true);
                break;
            case 'vegan':
                $query->where('is_vegan', true);
                break;
            case 'gluten_free':
                $query->where('is_gluten_free', true);
                break;
            case 'halal':
                $query->where('is_halal', true);
                break;
        }
    }
    
    // Apply price range filter
    if ($priceRange) {
        switch ($priceRange) {
            case 'under_10':
                $query->where(function($q) {
                    $q->where('discounted_price', '<', 10)
                      ->orWhere(function($q2) {
                          $q2->whereNull('discounted_price')->where('price', '<', 10);
                      });
                });
                break;
            case '10_20':
                $query->where(function($q) {
                    $q->whereBetween('discounted_price', [10, 20])
                      ->orWhere(function($q2) {
                          $q2->whereNull('discounted_price')->whereBetween('price', [10, 20]);
                      });
                });
                break;
            case '20_30':
                $query->where(function($q) {
                    $q->whereBetween('discounted_price', [20, 30])
                      ->orWhere(function($q2) {
                          $q2->whereNull('discounted_price')->whereBetween('price', [20, 30]);
                      });
                });
                break;
            case 'above_30':
                $query->where(function($q) {
                    $q->where('discounted_price', '>', 30)
                      ->orWhere(function($q2) {
                          $q2->whereNull('discounted_price')->where('price', '>', 30);
                      });
                });
                break;
        }
    }
    
    // Get all matching items
    $allItems = $query->get();
    
    // Group items by category and randomize within each category
    $menuByCategory = $allItems->groupBy(function($item) {
        return $item->menuCategory ? $item->menuCategory->name : 'Uncategorized';
    })->map(function($items) {
        // Randomize items within each category on every page load
        return $items->shuffle();
    });
    
    // Randomize the order of categories themselves
    $menuByCategory = $menuByCategory->shuffle();
    
    // Get all categories for filter dropdown
    $categories = MenuCategory::where('status', 'active')
        ->orderBy('category_name')
        ->get();
    
    // Get meal type counts for badges
    $mealTypeCounts = [
        'breakfast' => MenuItem::where('is_available', true)
            ->whereHas('menuCategory', fn($q) => $q->where('name', 'like', '%breakfast%'))
            ->count(),
        'lunch' => MenuItem::where('is_available', true)
            ->whereHas('menuCategory', fn($q) => $q->where('name', 'like', '%lunch%'))
            ->count(),
        'dinner' => MenuItem::where('is_available', true)
            ->whereHas('menuCategory', fn($q) => $q->where('name', 'like', '%dinner%'))
            ->count(),
    ];
    
    // Statistics
    $totalItems = $allItems->count();
    $featuredItems = $allItems->where('is_featured', true);
    
    return view('out.home.dailymenuitems', compact(
        'menuByCategory',
        'categories',
        'mealTypeCounts',
        'totalItems',
        'featuredItems',
        'search',
        'mealType',
        'dietaryFilter',
        'priceRange'
    ));
}

/**
 * Add daily meal item to cart
 */
public function addDailyMealToCart(Request $request)
{
    $validated = $request->validate([
        'item_id' => 'required|exists:menu_items,id',
        'quantity' => 'required|integer|min:1|max:50',
        'subscription_type' => 'nullable|in:one_time,daily,weekly,monthly',
    ]);
    
    try {
        $item = MenuItem::with(['supplier', 'menuCategory'])
            ->findOrFail($validated['item_id']);
        
        // Check availability
        if (!$item->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'This item is currently unavailable'
            ], 400);
        }
        
        // Check stock
        if ($item->stock_quantity !== null && $item->stock_quantity < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available'
            ], 400);
        }
        
        // Get current cart from session
        $cart = session()->get('cart', []);
        
        // Create cart item key
        $cartKey = 'item_' . $item->id;
        
        // Check if item already exists in cart
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $validated['quantity'];
        } else {
            $cart[$cartKey] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->discounted_price ?? $item->price,
                'quantity' => $validated['quantity'],
                'image' => $item->image_url ? asset('storage/' . $item->image_url) : asset('images/default-food.jpg'),
                'supplier_id' => $item->supplier_id,
                'supplier_name' => $item->supplier->business_name ?? 'N/A',
                'category' => $item->menuCategory->name ?? 'N/A',
                'subscription_type' => $validated['subscription_type'] ?? 'one_time',
            ];
        }
        
        // Save cart to session
        session()->put('cart', $cart);
        
        // Calculate cart totals
        $cartTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $cartItemCount = collect($cart)->sum('quantity');
        
        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully!',
            'cart_count' => $cartItemCount,
            'cart_total' => number_format($cartTotal, 2)
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Add to Cart Error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to add item to cart'
        ], 500);
    }
}


}