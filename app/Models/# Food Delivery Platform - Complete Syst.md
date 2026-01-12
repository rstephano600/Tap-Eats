# Food Delivery Platform - Complete System Processes

## Table of Contents
1. [User Registration & Authentication](#1-user-registration--authentication)
2. [Guest Session Management](#2-guest-session-management)
3. [Location Management](#3-location-management)
4. [Supplier Onboarding](#4-supplier-onboarding)
5. [Menu Management](#5-menu-management)
6. [Instant Order Process](#6-instant-order-process)
7. [Daily Meal Subscription Process](#7-daily-meal-subscription-process)
8. [Catering Request Process](#8-catering-request-process)
9. [Delivery Partner Management](#9-delivery-partner-management)
10. [Payment Processing](#10-payment-processing)
11. [Review & Rating System](#11-review--rating-system)
12. [Coupon & Promotion System](#12-coupon--promotion-system)
13. [Notification System](#13-notification-system)
14. [Supplier Payout Process](#14-supplier-payout-process)

---

## 1. User Registration & Authentication

### 1.1 Customer Registration Process

**Database Tables Involved:**
- `users`
- `customer_profiles`
- `customer_addresses`

**Process Flow:**

```
START
  ↓
1. User submits registration form
   - Email
   - Phone
   - Password
   ↓
2. System validates input
   - Check email uniqueness (users.email)
   - Check phone uniqueness (users.phone)
   - Validate password strength
   ↓
3. Create user record
   INSERT INTO users (
     email, phone, password, user_type='customer', 
     status='pending_verification'
   )
   ↓
4. Generate verification code
   UPDATE users SET verification_code = RANDOM(6_DIGITS)
   ↓
5. Send verification SMS/Email
   - SMS to phone
   - Email to email address
   ↓
6. User enters verification code
   ↓
7. Verify code
   IF verification_code matches THEN
     UPDATE users SET 
       phone_verified_at = NOW(),
       email_verified_at = NOW(),
       status = 'active'
   ↓
8. Create customer profile
   INSERT INTO customer_profiles (
     user_id, first_name, last_name,
     email_notifications = true,
     sms_notifications = true,
     loyalty_points = 0
   )
   ↓
9. Generate authentication token
   - Create session
   - Return JWT token
   ↓
END
```

**Implementation Example:**

```php
// CustomerRegistrationService.php
class CustomerRegistrationService
{
    public function register(array $data): User
    {
        DB::beginTransaction();
        
        try {
            // 1. Create user
            $user = User::create([
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'user_type' => 'customer',
                'status' => 'pending_verification',
                'verification_code' => $this->generateCode(),
            ]);
            
            // 2. Create customer profile
            $user->customerProfile()->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email_notifications' => true,
                'sms_notifications' => true,
                'loyalty_points' => 0,
            ]);
            
            // 3. Send verification
            $this->sendVerification($user);
            
            DB::commit();
            return $user;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function verifyCode(User $user, string $code): bool
    {
        if ($user->verification_code === $code) {
            $user->update([
                'phone_verified_at' => now(),
                'email_verified_at' => now(),
                'status' => 'active',
                'verification_code' => null,
            ]);
            return true;
        }
        return false;
    }
}
```

---

## 2. Guest Session Management

### 2.1 Guest Session Creation & Tracking

**Database Tables Involved:**
- `guest_sessions`
- `guest_carts`
- `customer_addresses`

**Process Flow:**

```
START (User visits website without login)
  ↓
1. Check for existing session
   SELECT * FROM guest_sessions 
   WHERE session_token = COOKIE_TOKEN 
   AND expires_at > NOW()
   ↓
   IF session exists THEN
     Update last_activity_at
   ELSE
     Create new session
   ↓
2. Create guest session
   INSERT INTO guest_sessions (
     session_token = GENERATE_UNIQUE_TOKEN(),
     device_id = BROWSER_FINGERPRINT,
     ip_address = CLIENT_IP,
     user_agent = BROWSER_USER_AGENT,
     expires_at = NOW() + 24 HOURS
   )
   ↓
3. Store session token in cookie
   SET_COOKIE('guest_token', session_token, 24h)
   ↓
4. Detect user location (if permission granted)
   IF geolocation_available THEN
     UPDATE guest_sessions SET
       latitude = USER_LAT,
       longitude = USER_LNG,
       location_address = REVERSE_GEOCODE(LAT, LNG)
   ↓
5. Track activity
   - Update last_activity_at on each request
   - Store cart items in guest_carts
   - Store addresses in customer_addresses
   ↓
END
```

**Cart Management for Guests:**

```
GUEST ADDS ITEM TO CART
  ↓
1. Validate guest session
   SELECT * FROM guest_sessions 
   WHERE session_token = TOKEN
   AND expires_at > NOW()
   ↓
2. Check if item already in cart
   SELECT * FROM guest_carts
   WHERE guest_session_id = SESSION_ID
   AND menu_item_id = ITEM_ID
   AND variant_id = VARIANT_ID
   ↓
   IF exists THEN
     UPDATE quantity = quantity + NEW_QUANTITY
   ELSE
     INSERT new cart item
   ↓
3. Calculate item total
   item_total = (base_price + variant_adjustment + addons) * quantity
   ↓
4. Return updated cart
   SELECT gc.*, mi.name, mi.price, s.business_name
   FROM guest_carts gc
   JOIN menu_items mi ON gc.menu_item_id = mi.id
   JOIN suppliers s ON gc.supplier_id = s.id
   WHERE gc.guest_session_id = SESSION_ID
   ↓
END
```

**Guest to Registered User Conversion:**

```
GUEST CREATES ACCOUNT
  ↓
1. Create user account (as per registration process)
   ↓
2. Migrate guest data
   BEGIN TRANSACTION
   
   a) Migrate cart items
      UPDATE guest_carts
      SET user_id = NEW_USER_ID
      WHERE guest_session_id = SESSION_ID
      
      Then move to user_carts:
      INSERT INTO user_carts 
      SELECT NULL, NEW_USER_ID, menu_item_id, supplier_id, ...
      FROM guest_carts
      WHERE guest_session_id = SESSION_ID
   
   b) Migrate addresses
      UPDATE customer_addresses
      SET user_id = NEW_USER_ID, guest_session_id = NULL
      WHERE guest_session_id = SESSION_ID
   
   c) Migrate orders (if any placed)
      UPDATE orders
      SET customer_id = NEW_USER_ID, guest_session_id = NULL
      WHERE guest_session_id = SESSION_ID
   
   COMMIT TRANSACTION
   ↓
END
```

**Order Status Management:**

```
ORDER STATUS WORKFLOW
  ↓
pending → accepted → preparing → ready → dispatched → delivered
    ↓         ↓          ↓
cancelled  rejected   failed

SUPPLIER ACCEPTS ORDER
  ↓
1. Supplier receives notification
   SELECT * FROM orders
   WHERE supplier_id = SUPPLIER_ID
   AND order_status = 'pending'
   ORDER BY created_at DESC
   ↓
2. Supplier reviews order within 5 minutes
   ↓
   IF accepts THEN
     UPDATE orders SET
       order_status = 'accepted',
       accepted_at = NOW()
     
     INSERT INTO order_status_history (
       order_id, old_status = 'pending',
       new_status = 'accepted',
       changed_by = SUPPLIER_USER_ID,
       changed_at = NOW()
     )
     
     Notify customer: "Order accepted"
     
   ELSE IF rejects THEN
     UPDATE orders SET
       order_status = 'rejected',
       rejection_reason = REASON
     
     Initiate refund (if paid)
     
     Notify customer: "Order rejected"
     RETURN
   ↓
3. Auto-assign or manually assign delivery partner
   CALL DeliveryAssignmentService
   ↓
4. Supplier marks order as preparing
   UPDATE orders SET
     order_status = 'preparing',
     prepared_at = NOW()
   ↓
5. Supplier marks order as ready
   UPDATE orders SET
     order_status = 'ready'
   
   Notify delivery partner: "Order ready for pickup"
   ↓
6. Generate OTP for pickup
   otp = RANDOM(6_DIGITS)
   
   INSERT INTO deliveries (
     order_id,
     delivery_partner_id,
     pickup_location_id,
     pickup_latitude,
     pickup_longitude,
     delivery_latitude,
     delivery_longitude,
     pickup_otp = otp,
     distance_km = CALCULATE_DISTANCE(),
     estimated_time_minutes = 30,
     status = 'assigned'
   )
   ↓
END
```

**Implementation:**

```php
// OrderService.php
class OrderService
{
    public function createOrder(array $cartData, array $addressData, ?string $couponCode = null): Order
    {
        DB::beginTransaction();
        
        try {
            // 1. Get cart items
            $cartItems = $this->getCartItems($cartData['user_id'] ?? null, $cartData['session_id'] ?? null);
            
            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }
            
            // 2. Validate supplier can deliver
            $supplier = Supplier::findOrFail($cartItems->first()->supplier_id);
            
            if (!$supplier->canDeliver($addressData['latitude'], $addressData['longitude'])) {
                throw new \Exception('Supplier cannot deliver to this address');
            }
            
            // 3. Apply coupon
            $discount = 0;
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->active()->first();
                
                if (!$coupon) {
                    throw new \Exception('Invalid coupon code');
                }
                
                $subtotal = $cartItems->sum('item_total');
                $discount = $coupon->calculateDiscount($subtotal);
            }
            
            // 4. Calculate totals
            $subtotal = $cartItems->sum('item_total');
            $deliveryFee = $supplier->calculateDeliveryFee($subtotal);
            $serviceFee = $subtotal * 0.02;
            $taxAmount = ($subtotal + $deliveryFee) * 0.18;
            $totalAmount = $subtotal + $deliveryFee + $serviceFee + $taxAmount - $discount;
            
            // 5. Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $cartData['user_id'] ?? null,
                'guest_session_id' => $cartData['session_id'] ?? null,
                'supplier_id' => $supplier->id,
                'service_type_id' => 1, // Instant delivery
                'order_type' => 'instant',
                'order_status' => 'pending',
                'payment_method' => $cartData['payment_method'],
                'payment_status' => 'pending',
                'delivery_address_id' => $addressData['id'] ?? null,
                'delivery_address_text' => $addressData['full_address'],
                'delivery_latitude' => $addressData['latitude'],
                'delivery_longitude' => $addressData['longitude'],
                'delivery_phone' => $addressData['phone'],
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'service_fee' => $serviceFee,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discount,
                'coupon_code' => $couponCode,
                'total_amount' => $totalAmount,
                'special_instructions' => $cartData['instructions'] ?? null,
                'estimated_delivery_time' => 45,
            ]);
            
            // 6. Create order items
            foreach ($cartItems as $cartItem) {
                $order->items()->create([
                    'menu_item_id' => $cartItem->menu_item_id,
                    'item_name' => $cartItem->menuItem->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->menuItem->current_price,
                    'variant_id' => $cartItem->variant_id,
                    'variant_name' => $cartItem->variant->variant_name ?? null,
                    'selected_addons' => $cartItem->selected_addons,
                    'addons_total' => $this->calculateAddonsTotal($cartItem->selected_addons),
                    'special_instructions' => $cartItem->special_instructions,
                    'subtotal' => $cartItem->item_total,
                ]);
                
                // Update menu item order count
                $cartItem->menuItem->incrementOrders();
            }
            
            // 7. Record coupon usage
            if ($couponCode && $discount > 0) {
                $coupon->usage()->create([
                    'user_id' => $cartData['user_id'] ?? null,
                    'order_id' => $order->id,
                    'discount_amount' => $discount,
                ]);
                $coupon->incrementUsage();
            }
            
            // 8. Process payment
            if ($cartData['payment_method'] !== 'cash') {
                $payment = $this->paymentService->processPayment($order, $cartData['payment_method']);
                
                if ($payment->status !== 'completed') {
                    throw new \Exception('Payment failed: ' . $payment->failure_reason);
                }
            }
            
            // 9. Record status history
            $order->statusHistory()->create([
                'old_status' => null,
                'new_status' => 'pending',
                'changed_at' => now(),
            ]);
            
            // 10. Clear cart
            $this->clearCart($cartData['user_id'] ?? null, $cartData['session_id'] ?? null);
            
            // 11. Send notifications
            $order->supplier->user->notify(new NewOrderReceived($order));
            
            if ($order->customer) {
                $order->customer->notify(new OrderPlaced($order));
            }
            
            DB::commit();
            return $order;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function acceptOrder(Order $order, User $supplier): void
    {
        $order->updateStatus('accepted', $supplier->id, 'Order accepted by supplier');
        
        // Notify customer
        if ($order->customer) {
            $order->customer->notify(new OrderAccepted($order));
        }
    }
    
    public function rejectOrder(Order $order, User $supplier, string $reason): void
    {
        DB::transaction(function () use ($order, $supplier, $reason) {
            $order->update([
                'order_status' => 'rejected',
                'rejection_reason' => $reason,
            ]);
            
            // Initiate refund if paid
            if ($order->payment_status === 'paid') {
                $this->paymentService->refund($order);
            }
            
            // Notify customer
            if ($order->customer) {
                $order->customer->notify(new OrderRejected($order, $reason));
            }
        });
    }
}
```

---

## 7. Daily Meal Subscription Process

**Database Tables Involved:**
- `subscriptions`
- `subscription_deliveries`
- `orders`

**Process Flow:**

```
CUSTOMER SUBSCRIBES TO DAILY MEALS
  ↓
1. Customer selects subscription plan
   - Plan type (daily/weekly/monthly)
   - Meal times (breakfast/lunch/dinner)
   - Start date
   - Dietary preferences
   ↓
2. Select delivery address & schedule
   - Delivery address
   - Delivery days (Mon-Sun)
   - Preferred delivery time
   ↓
3. Calculate subscription price
   price_per_meal = supplier.meal_price
   meals_per_week = COUNT(selected_days) * COUNT(meal_times)
   price_per_period = price_per_meal * meals_per_week
   ↓
4. Create subscription
   INSERT INTO subscriptions (
     subscription_number = GENERATE_SUB_NUMBER(),
     customer_id,
     supplier_id,
     plan_type,
     meal_times (JSON),
     meals_per_day,
     dietary_preferences (JSON),
     delivery_address_id,
     delivery_schedule (JSON),
     preferred_delivery_time,
     price_per_meal,
     price_per_period,
     start_date,
     next_billing_date = start_date + 7 days,
     billing_cycle_days = 7,
     status = 'pending',
     auto_renew = true
   )
   ↓
5. Process initial payment
   CALL PaymentService.charge(
     customer, price_per_period
   )
   
   IF payment_success THEN
     UPDATE subscriptions SET status = 'active'
   ELSE
     UPDATE subscriptions SET status = 'pending'
     RETURN payment_failed
   ↓
6. Generate delivery schedule
   FOR each_day IN subscription_period:
     IF day IN delivery_schedule THEN
       FOR each_meal_time IN meal_times:
         INSERT INTO subscription_deliveries (
           subscription_id,
           delivery_date = each_day,
           meal_time = each_meal_time,
           status = 'scheduled'
         )
   ↓
7. Send confirmation
   Notify customer: Subscription confirmed
   Notify supplier: New subscription
   ↓
END

DAILY SUBSCRIPTION FULFILLMENT
  ↓
1. Daily job runs at 6 AM
   SELECT * FROM subscription_deliveries
   WHERE delivery_date = TODAY
   AND status = 'scheduled'
   ↓
2. For each scheduled delivery
   a) Create order
      INSERT INTO orders (
        order_number,
        customer_id,
        supplier_id,
        order_type = 'subscription',
        order_status = 'pending',
        ... (order details)
      )
   
   b) Link to subscription delivery
      UPDATE subscription_deliveries
      SET order_id = NEW_ORDER_ID
      WHERE id = DELIVERY_ID
   
   c) Notify supplier
      Send notification: "Prepare subscription meal"
   ↓
3. Order flows through normal delivery process
   ↓
4. On successful delivery
   UPDATE subscription_deliveries
   SET status = 'delivered',
       delivered_at = NOW()
   ↓
END

SUBSCRIPTION BILLING
  ↓
1. Daily job checks for due subscriptions
   SELECT * FROM subscriptions
   WHERE status = 'active'
   AND next_billing_date = TODAY
   ↓
2. For each due subscription
   a) Calculate amount
      amount = price_per_period
   
   b) Process payment
      CALL PaymentService.charge(customer, amount)
      
      IF payment_success THEN
        UPDATE subscriptions SET
          next_billing_date = DATE_ADD(next_billing_date, 
                                      billing_cycle_days)
      ELSE
        -- Retry payment
        IF retry_count >= 3 THEN
          UPDATE subscriptions SET
            status = 'paused',
            paused_at = NOW()
          
          Notify customer: Payment failed, subscription paused
   ↓
END

CUSTOMER PAUSES/CANCELS SUBSCRIPTION
  ↓
1. Customer requests pause/cancellation
   ↓
2. IF pause THEN
     UPDATE subscriptions SET
       status = 'paused',
       paused_at = NOW()
     
     Cancel future scheduled deliveries
     
   ELSE IF cancel THEN
     UPDATE subscriptions SET
       status = 'cancelled',
       cancelled_at = NOW(),
       cancellation_reason = REASON
     
     Cancel all future deliveries
     
     IF prorated_refund_applicable THEN
       Calculate refund for unused days
       Process refund
   ↓
END
```

**Implementation:**

```php
// SubscriptionService.php
class SubscriptionService
{
    public function createSubscription(User $customer, array $data): Subscription
    {
        DB::beginTransaction();
        
        try {
            $supplier = Supplier::findOrFail($data['supplier_id']);
            
            // Calculate pricing
            $mealsPerWeek = count($data['meal_times']) * count($data['delivery_days']);
            $pricePerPeriod = $data['price_per_meal'] * $mealsPerWeek;
            
            // Create subscription
            $subscription = $customer->subscriptions()->create([
                'subscription_number' => Subscription::generateSubscriptionNumber(),
                'supplier_id' => $supplier->id,
                'plan_type' => $data['plan_type'],
                'meal_times' => $data['meal_times'],
                'meals_per_day' => count($data['meal_times']),
                'dietary_preferences' => $data['dietary_preferences'] ?? [],
                'delivery_address_id' => $data['address_id'],
                'delivery_schedule' => $data['delivery_days'],
                'preferred_delivery_time' => $data['delivery_time'],
                'price_per_meal' => $data['price_per_meal'],
                'price_per_period' => $pricePerPeriod,
                'start_date' => $data['start_date'],
                'next_billing_date' => Carbon::parse($data['start_date'])->addDays(7),
                'billing_cycle_days' => 7,
                'status' => 'pending',
                'auto_renew' => $data['auto_renew'] ?? true,
            ]);
            
            // Process initial payment
            $payment = $this->paymentService->charge(
                $customer,
                $pricePerPeriod,
                "Subscription {$subscription->subscription_number}"
            );
            
            if ($payment->status === 'completed') {
                $subscription->update(['status' => 'active']);
            } else {
                throw new \Exception('Payment failed');
            }
            
            // Generate delivery schedule
            $this->generateDeliverySchedule($subscription, 7); // 7 days
            
            // Notifications
            $customer->notify(new SubscriptionCreated($subscription));
            $supplier->user->notify(new NewSubscription($subscription));
            
            DB::commit();
            return $subscription;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    protected function generateDeliverySchedule(Subscription $subscription, int $days): void
    {
        $startDate = $subscription->start_date;
        $deliveryDays = $subscription->delivery_schedule; // ['monday', 'wednesday', 'friday']
        
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayName = strtolower($date->format('l'));
            
            if (in_array($dayName, $deliveryDays)) {
                foreach ($subscription->meal_times as $mealTime) {
                    $subscription->deliveries()->create([
                        'delivery_date' => $date,
                        'meal_time' => $mealTime,
                        'status' => 'scheduled',
                    ]);
                }
            }
        }
    }
    
    public function pause(Subscription $subscription): void
    {
        $subscription->pause();
        
        // Cancel future scheduled deliveries
        $subscription->deliveries()
            ->where('delivery_date', '>', now())
            ->where('status', 'scheduled')
            ->delete();
    }
    
    public function cancel(Subscription $subscription, string $reason): void
    {
        DB::transaction(function () use ($subscription, $reason) {
            $subscription->cancel($reason);
            
            // Cancel all future deliveries
            $subscription->deliveries()
                ->where('delivery_date', '>=', now())
                ->where('status', 'scheduled')
                ->delete();
            
            // Calculate prorated refund if applicable
            // ... refund logic
        });
    }
}

// Console/Commands/ProcessDailySubscriptions.php
class ProcessDailySubscriptions extends Command
{
    public function handle()
    {
        $todayDeliveries = SubscriptionDelivery::where('delivery_date', today())
            ->where('status', 'scheduled')
            ->with(['subscription.customer', 'subscription.supplier'])
            ->get();
        
        foreach ($todayDeliveries as $delivery) {
            $this->createSubscriptionOrder($delivery);
        }
    }
    
    protected function createSubscriptionOrder(SubscriptionDelivery $delivery)
    {
        $subscription = $delivery->subscription;
        
        // Create order for this delivery
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_id' => $subscription->customer_id,
            'supplier_id' => $subscription->supplier_id,
            'service_type_id' => 2, // Daily meals
            'order_type' => 'subscription',
            'order_status' => 'pending',
            'delivery_address_id' => $subscription->delivery_address_id,
            // ... other fields
        ]);
        
        // Link to subscription delivery
        $delivery->update(['order_id' => $order->id]);
        
        // Notify supplier
        $subscription->supplier->user->notify(
            new SubscriptionOrderReady($order, $delivery)
        );
    }
}
```

---

## 8. Catering Request Process

**Database Tables Involved:**
- `catering_requests`
- `catering_proposals`
- `orders`

**Process Flow:**

```
CUSTOMER REQUESTS CATERING
  ↓
1. Customer fills catering request form
   - Event type (wedding/corporate/birthday)
   - Event date & time
   - Guest count
   - Venue address
   - Service type (buffet/plated/cocktail)
   - Cuisine preferences
   - Dietary requirements
   - Budget range
   ↓
2. Validate event date
   IF event_date < NOW() + 3_DAYS THEN
     RETURN "Minimum 3 days advance booking required"
   ↓
3. Geocode venue address
   coordinates = GEOCODE(venue_address)
   ↓
4. Find nearby suppliers offering catering
   SELECT s.* FROM suppliers s
   JOIN supplier_service_types sst ON s.id = sst.supplier_id
   JOIN service_types st ON sst.service_type_id = st.id
   WHERE st.slug = 'catering'
   AND s.is_active = true
   AND DISTANCE(s.location, venue_location) < 20km
   ↓
5. Create catering request
   INSERT INTO catering_requests (
     request_number = GENERATE_REQUEST_NUMBER(),
     customer_id (or guest_session_id),
     event_type, event_date, event_time,
     guest_count, venue_address,
     venue_latitude, venue_longitude,
     service_type, cuisine_preferences (JSON),
     dietary_requirements (JSON),
     budget_min, budget_max,
     contact_name, contact_email, contact_phone,
     status = 'pending'
   )
   ↓
6. Notify eligible suppliers
   FOR EACH supplier IN nearby_suppliers:
     Send notification: "New catering request"
     Email with request details
   ↓
7. Customer receives confirmation
   "Your request has been sent to X suppliers"
   ↓
END

SUPPLIER SUBMITS PROPOSAL
  ↓
1. Supplier reviews catering request
   SELECT * FROM catering_requests
   WHERE status = 'pending'
   AND event_date >= NOW()
   ↓
2. Supplier creates proposal
   INSERT INTO catering_proposals (
     catering_request_id,
     supplier_id,
     proposal_number = GENERATE_PROPOSAL_NUMBER(),
     menu_items (JSON), -- Proposed menu
     price_per_person,
     total_price,
     setup_fee, service_fee,
     inclusions (TEXT), -- What's included
     exclusions (TEXT), -- What's not included
     includes_setup, includes_service_staff,
     includes_equipment, includes_decoration,
     staff_count,
     valid_until = NOW() + 7_DAYS,
     notes (TEXT),
     status = 'submitted',
     submitted_at = NOW()
   )
   ↓
3. Notify customer
   Email: "You have a new catering proposal"
   Push notification
   ↓
END

CUSTOMER REVIEWS PROPOSALS
  ↓
1. Customer views all proposals
   SELECT cp.*, s.business_name, s.average_rating
   FROM catering_proposals cp
   JOIN suppliers s ON cp.supplier_id = s.id
   WHERE cp.catering_request_id = REQUEST_ID
   AND cp.status IN ('submitted', 'viewed')
   ORDER BY cp.total_price ASC
   ↓
2. Customer opens proposal
   UPDATE catering_proposals
   SET status = 'viewed',
       viewed_at = NOW()
   WHERE id = PROPOSAL_ID
   ↓
3. Customer compares proposals
   - Price comparison
   - Menu comparison
   - Supplier ratings
   - Inclusions/exclusions
   ↓
4. Customer accepts a proposal
   BEGIN TRANSACTION
   
   UPDATE catering_proposals
   SET status = 'accepted',
       accepted_at = NOW()
   WHERE id = ACCEPTED_PROPOSAL_ID
   
   UPDATE catering_proposals
   SET status = 'rejected'
   WHERE catering_request_id = REQUEST_ID
   AND id != ACCEPTED_PROPOSAL_ID
   
   UPDATE catering_requests
   SET status = 'accepted'
   WHERE id = REQUEST_ID
   
   COMMIT TRANSACTION
   ↓
5. Create catering order
   INSERT INTO orders (
     order_number,
     customer_id,
     supplier_id,
     service_type_id = 3, -- Catering
     order_type = 'catering',
     order_status = 'pending',
     scheduled_at = event_date + event_time,
     total_amount = proposal.total_price,
     ...
   )
   ↓
6. Process advance payment (30-50%)
   advance_amount = total_amount * 0.50
   
   CALL PaymentService.charge(customer, advance_amount)
   ↓
7. Notify supplier
   "Your catering proposal was accepted!"
   Include event details and customer contact
   ↓
END

CATERING EVENT EXECUTION
  ↓
1. Pre-event coordination (3 days before)
   - Supplier confirms menu
   - Confirms staff count
   - Confirms equipment needs
   ↓
2. Day before event
   - Supplier prepares
   - Confirms arrival time
   ↓
3. Event day
   - Supplier arrives at venue
   - Setup
   - Service
   - Cleanup
   ↓
4. After event
   UPDATE orders SET
     order_status = 'delivered',
     delivered_at = NOW()
   ↓
5. Final payment
   remaining_amount = total_amount - advance_paid
   
   CALL PaymentService.charge(customer, remaining_amount)
   ↓
6. Request review
   Send notification: "How was your catering experience?"
   ↓
END
```

**Implementation:**

```php
// CateringService.php
class CateringService
{
    public function createRequest(array $data): CateringRequest
    {
        // Validate event date
        $eventDate = Carbon::parse($data['event_date']);
        
        if ($eventDate->lt(now()->addDays(3))) {
            throw new \Exception('Minimum 3 days advance booking required');
        }
        
        // Geocode venue
        $coordinates = $this->locationService->geocodeAddress($data['venue_address']);
        
        // Create request
        $request = CateringRequest::create([
            'request_number' => CateringRequest::generateRequestNumber(),
            'customer_id' => $data['customer_id'] ?? null,
            'guest_session_id' => $data['session_id'] ?? null,
            'event_type' => $data['event_type'],
            'event_date' => $data['event_date'],
            'event_time' => $data['event_time'],
            'guest_count' => $data['guest_count'],
            'venue_address' => $data['venue_address'],
            'venue_latitude' => $coordinates['latitude'],
            'venue_longitude' => $coordinates['longitude'],
            'service_type' => $data['service_type'],
            'cuisine_preferences' => $data['cuisine_preferences'],
            'dietary_requirements' => $data['dietary_requirements'],
            'budget_min' => $data['budget_min'],
            'budget_max' => $data['budget_max'],
            'contact_name' => $data['contact_name'],
            'contact_email' => $data['contact_email'],
            'contact_phone' => $data['contact_phone'],
            'status' => 'pending',
        ]);
        
        // Find and notify eligible suppliers
        $suppliers = $this->findEligibleCaterers(
            $coordinates['latitude'],
            $coordinates['longitude']
        );
        
        foreach ($suppliers as $supplier) {
            $supplier->user->notify(new NewCateringRequest($request));
        }
        
        return $request;
    }
    
    protected function findEligibleCaterers($latitude, $longitude, $radiusKm = 20)
    {
        return Supplier::active()
            ->byServiceType(3) // Catering service type
            ->withinRadius($latitude, $longitude, $radiusKm)
            ->get();
    }
    
    public function submitProposal(Supplier $supplier, CateringRequest $request, array $data): CateringProposal
    {
        $proposal = $request->proposals()->create([
            'supplier_id' => $supplier->id,
            'proposal_number' => CateringProposal::generateProposalNumber(),
            'menu_items' => $data['menu_items'],
            'price_per_person' => $data['price_per_person'],
            'total_price' => $data['total_price'],
            'setup_fee' => $data['setup_fee'] ?? 0,
            'service_fee' => $data['service_fee'] ?? 0,
            'inclusions' => $data['inclusions'],
            'exclusions' => $data['exclusions'] ?? null,
            'includes_setup' => $data['includes_setup'] ?? false,
            'includes_service_staff' => $data['includes_service_staff'] ?? false,
            'includes_equipment' => $data['includes_equipment'] ?? false,
            'includes_decoration' => $data['includes_decoration'] ?? false,
            'staff_count' => $data['staff_count'] ?? null,
            'valid_until' => now()->addDays(7),
            'notes' => $data['notes'] ?? null,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        
        // Notify customer
        if ($request->customer) {
            $request->customer->notify(new NewCateringProposal($proposal));
        }
        
        return $proposal;
    }
    
    public function acceptProposal(CateringProposal $proposal): Order
    {
        DB::transaction(function () use ($proposal) {
            // Accept this proposal
            $proposal->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);
            
            // Reject other proposals
            $proposal->cateringRequest->proposals()
                ->where('id', '!=', $proposal->id)
                ->update(['status' => 'rejected']);
            
            // Update request status
            $proposal->cateringRequest->update(['status' => 'accepted']);
            
            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $proposal->cateringRequest->customer_id,
                'supplier_id' => $proposal->supplier_id,
                'service_type_id' => 3, // Catering
                'order_type' => 'catering',
                'order_status' => 'pending',
                'scheduled_at' => Carbon::parse(
                    $proposal->cateringRequest->event_date . ' ' . 
                    $proposal->cateringRequest->event_time
                ),
                'total_amount' => $proposal->total_price,
                // ... other fields
            ]);
            
            // Process advance payment (50%)
            $advanceAmount = $proposal->total_price * 0.5;
            $this->paymentService->charge(
                $proposal->cateringRequest->customer,
                $advanceAmount,
                "Catering advance payment - {$order->order_number}"
            );
            
            // Notify supplier
            $proposal->supplier->user->notify(new CateringProposalAccepted($proposal, $order));
            
            return $order;
        });
    }
}
```

---

## 9. Delivery Partner Management

**Database Tables Involved:**
- `delivery_partners`
- `deliveries`
- `orders`

**Process Flow:**

```
DELIVERY PARTNER ONBOARDING
  ↓
1. Partner submits application
   - Personal details
   - Vehicle information
   - License number & expiry
   - ID number
   - Bank/mobile money details
   ↓
2. Create user account
   INSERT INTO users (
     email, phone, password,
     user_type = 'delivery_partner',
     status = 'pending_verification'
   )
   ↓
3. Create delivery partner profile
   INSERT INTO delivery_partners (
     user_id,
     first_name, last_name,
     phone, emergency_contact,
     vehicle_type, vehicle_plate,
     license_number, license_expiry,
     id_number,
     verification_status = 'pending',
     is_active = false,
     is_online = false,
     availability_status = 'offline'
   )
   ↓
4. Upload documents
   - License photo
   - ID photo
   - Vehicle registration
   ↓
5. Admin verification
   Review documents and information
   
   IF approved THEN
   ↓
3. Delete guest session
   DELETE FROM guest_sessions WHERE id = SESSION_ID
   ↓
4. Clear guest cookie and set auth token
   ↓
END
```

**Implementation:**

```php
// GuestSessionService.php
class GuestSessionService
{
    public function createOrRetrieve(Request $request): GuestSession
    {
        $token = $request->cookie('guest_token');
        
        if ($token) {
            $session = GuestSession::where('session_token', $token)
                ->where('expires_at', '>', now())
                ->first();
                
            if ($session) {
                $session->updateActivity();
                return $session;
            }
        }
        
        // Create new session
        $session = GuestSession::create([
            'session_token' => GuestSession::generateToken(),
            'device_id' => $this->getDeviceFingerprint($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'last_activity_at' => now(),
            'expires_at' => now()->addHours(24),
        ]);
        
        // Set cookie
        Cookie::queue('guest_token', $session->session_token, 1440); // 24h
        
        return $session;
    }
    
    public function migrateToUser(GuestSession $session, User $user): void
    {
        DB::transaction(function () use ($session, $user) {
            // Migrate cart
            foreach ($session->cart as $item) {
                UserCart::create([
                    'user_id' => $user->id,
                    'menu_item_id' => $item->menu_item_id,
                    'supplier_id' => $item->supplier_id,
                    'quantity' => $item->quantity,
                    'variant_id' => $item->variant_id,
                    'selected_addons' => $item->selected_addons,
                    'special_instructions' => $item->special_instructions,
                    'item_total' => $item->item_total,
                ]);
            }
            
            // Migrate addresses
            $session->addresses()->update([
                'user_id' => $user->id,
                'guest_session_id' => null,
            ]);
            
            // Migrate orders
            $session->orders()->update([
                'customer_id' => $user->id,
                'guest_session_id' => null,
            ]);
            
            // Delete guest session
            $session->delete();
        });
    }
}
```

---

## 3. Location Management

### 3.1 Location Detection & Address Management

**Database Tables Involved:**
- `customer_addresses`
- `supplier_locations`

**Process Flow:**

```
USER LOCATION DETECTION
  ↓
1. Request browser geolocation permission
   navigator.geolocation.getCurrentPosition()
   ↓
2. Receive coordinates
   latitude, longitude
   ↓
3. Reverse geocode to address
   CALL Google Maps Geocoding API
   OR Mapbox Geocoding API
   ↓
4. Store in session
   IF guest THEN
     UPDATE guest_sessions SET
       latitude = LAT,
       longitude = LNG,
       location_address = ADDRESS,
       city = CITY
   ELSE IF logged_in THEN
     Store in user preferences
   ↓
5. Find nearby suppliers
   SELECT s.*, sl.*,
     (6371 * acos(cos(radians(USER_LAT)) * 
      cos(radians(sl.latitude)) * 
      cos(radians(sl.longitude) - radians(USER_LNG)) + 
      sin(radians(USER_LAT)) * 
      sin(radians(sl.latitude)))) AS distance
   FROM suppliers s
   JOIN supplier_locations sl ON s.id = sl.supplier_id
   WHERE sl.is_active = true
   HAVING distance < 10
   ORDER BY distance
   ↓
END
```

**Add Delivery Address Process:**

```
USER ADDS NEW ADDRESS
  ↓
1. User enters address details
   - Address line 1, 2
   - City, postal code
   - Contact phone
   - Label (Home/Work/Other)
   ↓
2. Geocode address to coordinates
   CALL Geocoding API(address)
   RETURN latitude, longitude
   ↓
3. Validate address is within service area
   SELECT COUNT(*) FROM suppliers s
   JOIN supplier_locations sl ON s.id = sl.supplier_id
   WHERE (6371 * acos(...distance formula...)) < 15
   
   IF count = 0 THEN
     RETURN "Sorry, we don't deliver to this area yet"
   ↓
4. Save address
   INSERT INTO customer_addresses (
     user_id (or guest_session_id),
     address_line1, address_line2,
     city, postal_code,
     latitude, longitude,
     contact_phone, label,
     is_default = (user has no other addresses)
   )
   ↓
5. Set as default if first address
   IF (SELECT COUNT(*) FROM customer_addresses 
       WHERE user_id = USER_ID) = 1 THEN
     UPDATE customer_addresses SET is_default = true
   ↓
END
```

**Implementation:**

```php
// LocationService.php
class LocationService
{
    public function reverseGeocode($latitude, $longitude): array
    {
        // Using Google Maps Geocoding API
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'latlng' => "{$latitude},{$longitude}",
            'key' => config('services.google_maps.key'),
        ]);
        
        $result = $response->json();
        
        if ($result['status'] === 'OK') {
            $address = $result['results'][0];
            
            return [
                'address' => $address['formatted_address'],
                'city' => $this->extractComponent($address, 'locality'),
                'country' => $this->extractComponent($address, 'country'),
            ];
        }
        
        throw new \Exception('Geocoding failed');
    }
    
    public function geocodeAddress(string $address): array
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => config('services.google_maps.key'),
        ]);
        
        $result = $response->json();
        
        if ($result['status'] === 'OK') {
            $location = $result['results'][0]['geometry']['location'];
            
            return [
                'latitude' => $location['lat'],
                'longitude' => $location['lng'],
            ];
        }
        
        throw new \Exception('Address not found');
    }
    
    public function getNearbySuppliers($latitude, $longitude, $radiusKm = 10)
    {
        return Supplier::active()
            ->with('primaryLocation')
            ->withinRadius($latitude, $longitude, $radiusKm)
            ->get();
    }
    
    public function validateServiceArea($latitude, $longitude): bool
    {
        $nearbySuppliers = $this->getNearbySuppliers($latitude, $longitude, 15);
        return $nearbySuppliers->count() > 0;
    }
}
```

---

## 4. Supplier Onboarding

### 4.1 Supplier Registration Process

**Database Tables Involved:**
- `users`
- `suppliers`
- `supplier_locations`
- `supplier_service_types`
- `supplier_categories`

**Process Flow:**

```
SUPPLIER REGISTRATION
  ↓
1. Supplier submits application
   - Business name
   - Business type (restaurant/cafe/catering)
   - Contact information
   - License number
   - Tax ID
   - Bank details
   ↓
2. Create user account
   INSERT INTO users (
     email, phone, password,
     user_type = 'supplier',
     status = 'pending_verification'
   )
   ↓
3. Create supplier record
   INSERT INTO suppliers (
     user_id,
     business_name, business_type,
     license_number, tax_id,
     verification_status = 'pending',
     is_active = false,
     accepts_orders = false
   )
   ↓
4. Add business location
   INSERT INTO supplier_locations (
     supplier_id,
     address_line1, city, latitude, longitude,
     is_primary = true
   )
   ↓
5. Select service types
   INSERT INTO supplier_service_types
   (supplier_id, service_type_id)
   VALUES 
     (SUPPLIER_ID, 1), -- Instant delivery
     (SUPPLIER_ID, 3)  -- Catering
   ↓
6. Select categories/cuisines
   INSERT INTO supplier_categories
   (supplier_id, category_id)
   VALUES
     (SUPPLIER_ID, 5), -- Italian
     (SUPPLIER_ID, 12) -- Pizza
   ↓
7. Upload documents
   - Business license (PDF)
   - Health certificate (PDF)
   - Bank statement (PDF)
   Store URLs in suppliers table
   ↓
8. Admin verification process
   SELECT * FROM suppliers
   WHERE verification_status = 'pending'
   ↓
   Admin reviews:
   - Documents
   - Business information
   - Location
   ↓
   IF approved THEN
     UPDATE suppliers SET
       verification_status = 'verified',
       verified_at = NOW(),
       is_active = true,
       accepts_orders = true
       
     Send notification to supplier
   ELSE
     UPDATE suppliers SET
       verification_status = 'rejected'
     
     Send rejection email with reason
   ↓
9. Supplier dashboard access granted
   ↓
END
```

**Implementation:**

```php
// SupplierOnboardingService.php
class SupplierOnboardingService
{
    public function register(array $data): Supplier
    {
        DB::beginTransaction();
        
        try {
            // 1. Create user
            $user = User::create([
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'user_type' => 'supplier',
                'status' => 'pending_verification',
            ]);
            
            // 2. Create supplier
            $supplier = $user->supplier()->create([
                'business_name' => $data['business_name'],
                'slug' => Str::slug($data['business_name']),
                'business_type' => $data['business_type'],
                'description' => $data['description'],
                'contact_email' => $data['email'],
                'contact_phone' => $data['phone'],
                'license_number' => $data['license_number'],
                'tax_id' => $data['tax_id'],
                'verification_status' => 'pending',
                'is_active' => false,
                'accepts_orders' => false,
                'commission_rate' => 15.00, // Default
            ]);
            
            // 3. Add location
            $location = $this->locationService->geocodeAddress($data['address']);
            
            $supplier->locations()->create([
                'address_line1' => $data['address'],
                'city' => $data['city'],
                'postal_code' => $data['postal_code'],
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'phone' => $data['phone'],
                'is_primary' => true,
                'is_active' => true,
            ]);
            
            // 4. Attach service types
            $supplier->serviceTypes()->attach($data['service_type_ids']);
            
            // 5. Attach categories
            $supplier->categories()->attach($data['category_ids']);
            
            // 6. Send verification notification to admin
            Notification::send(
                User::where('user_type', 'admin')->get(),
                new NewSupplierRegistration($supplier)
            );
            
            DB::commit();
            return $supplier;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function approve(Supplier $supplier): void
    {
        $supplier->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'is_active' => true,
            'accepts_orders' => true,
        ]);
        
        // Send approval notification
        $supplier->user->notify(new SupplierApproved($supplier));
    }
    
    public function reject(Supplier $supplier, string $reason): void
    {
        $supplier->update([
            'verification_status' => 'rejected',
        ]);
        
        // Send rejection notification
        $supplier->user->notify(new SupplierRejected($supplier, $reason));
    }
}
```

---

## 5. Menu Management

### 5.1 Menu Creation Process

**Database Tables Involved:**
- `menu_categories`
- `menu_items`
- `menu_item_variants`
- `menu_item_addons`

**Process Flow:**

```
SUPPLIER CREATES MENU
  ↓
1. Create menu categories
   INSERT INTO menu_categories
   (supplier_id, category_name, display_order)
   VALUES
     (SUPPLIER_ID, 'Appetizers', 1),
     (SUPPLIER_ID, 'Main Course', 2),
     (SUPPLIER_ID, 'Desserts', 3)
   ↓
2. Add menu items
   FOR EACH item:
     INSERT INTO menu_items (
       supplier_id,
       menu_category_id,
       name, description, price,
       image_url,
       preparation_time,
       is_vegetarian, is_vegan, is_halal,
       allergens (JSON),
       ingredients (JSON),
       is_available = true,
       display_order
     )
   ↓
3. Add variants (optional)
   IF item has variants (sizes) THEN
     INSERT INTO menu_item_variants
     (menu_item_id, variant_name, price_adjustment)
     VALUES
       (ITEM_ID, 'Small', -2000),
       (ITEM_ID, 'Regular', 0),
       (ITEM_ID, 'Large', 3000)
   ↓
4. Add addons (optional)
   IF item has addons THEN
     INSERT INTO menu_item_addons
     (menu_item_id, addon_name, price, max_quantity)
     VALUES
       (ITEM_ID, 'Extra Cheese', 1500, 2),
       (ITEM_ID, 'Bacon', 2000, 1)
   ↓
5. Upload item images
   - Store in cloud storage (S3)
   - Update image_url in menu_items
   ↓
6. Publish menu
   UPDATE suppliers SET
     accepts_orders = true
   WHERE id = SUPPLIER_ID
   ↓
END
```

**Menu Item Availability Management:**

```
UPDATE ITEM AVAILABILITY
  ↓
SCENARIO 1: Manual toggle
  UPDATE menu_items SET
    is_available = NOT is_available
  WHERE id = ITEM_ID
  ↓
SCENARIO 2: Stock-based availability
  IF item has stock tracking THEN
    UPDATE menu_items SET
      stock_quantity = stock_quantity - ORDER_QUANTITY
    
    IF stock_quantity <= 0 THEN
      UPDATE menu_items SET is_available = false
  ↓
SCENARIO 3: Time-based availability
  Check available_times JSON
  IF current_time NOT IN available_times THEN
    Hide from customer view
  ↓
END
```

**Implementation:**

```php
// MenuManagementService.php
class MenuManagementService
{
    public function createMenuItem(Supplier $supplier, array $data): MenuItem
    {
        DB::beginTransaction();
        
        try {
            // 1. Create menu item
            $item = $supplier->menuItems()->create([
                'menu_category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'],
                'price' => $data['price'],
                'preparation_time' => $data['preparation_time'],
                'is_vegetarian' => $data['is_vegetarian'] ?? false,
                'is_vegan' => $data['is_vegan'] ?? false,
                'is_halal' => $data['is_halal'] ?? false,
                'allergens' => $data['allergens'] ?? [],
                'ingredients' => $data['ingredients'] ?? [],
                'is_available' => true,
            ]);
            
            // 2. Upload image
            if (isset($data['image'])) {
                $path = $data['image']->store('menu-items', 's3');
                $item->update(['image_url' => Storage::disk('s3')->url($path)]);
            }
            
            // 3. Add variants
            if (isset($data['variants'])) {
                foreach ($data['variants'] as $variant) {
                    $item->variants()->create($variant);
                }
            }
            
            // 4. Add addons
            if (isset($data['addons'])) {
                foreach ($data['addons'] as $addon) {
                    $item->addons()->create($addon);
                }
            }
            
            DB::commit();
            return $item;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function toggleAvailability(MenuItem $item): void
    {
        $item->update([
            'is_available' => !$item->is_available,
        ]);
        
        // Log change
        Log::info("Menu item availability changed", [
            'item_id' => $item->id,
            'is_available' => $item->is_available,
        ]);
    }
    
    public function updateStock(MenuItem $item, int $quantity): void
    {
        $item->decrement('stock_quantity', $quantity);
        
        if ($item->stock_quantity <= 0) {
            $item->update(['is_available' => false]);
            
            // Notify supplier
            $item->supplier->user->notify(
                new MenuItemOutOfStock($item)
            );
        }
    }
}
```

---

## 6. Instant Order Process

### 6.1 Complete Order Flow

**Database Tables Involved:**
- `orders`
- `order_items`
- `order_status_history`
- `payments`
- `deliveries`
- `user_carts` / `guest_carts`
- `customer_addresses`
- `coupons` / `coupon_usage`

**Process Flow:**

```
CUSTOMER PLACES ORDER
  ↓
1. Customer browses and adds items to cart
   FOR EACH item:
     IF logged_in THEN
       INSERT INTO user_carts
     ELSE
       INSERT INTO guest_carts
   ↓
2. Customer proceeds to checkout
   ↓
3. Select/Add delivery address
   SELECT * FROM customer_addresses
   WHERE (user_id = USER_ID OR guest_session_id = SESSION_ID)
   
   IF no address THEN
     User must add delivery address
   ↓
4. Validate delivery address
   Check if supplier can deliver to address:
   
   SELECT s.* FROM suppliers s
   JOIN supplier_locations sl ON s.id = sl.supplier_id
   WHERE s.id = SUPPLIER_ID
   AND (6371 * acos(...)) <= s.delivery_radius
   
   IF NOT deliverable THEN
     RETURN "Sorry, supplier doesn't deliver to your area"
   ↓
5. Apply coupon (optional)
   IF coupon_code provided THEN
     SELECT * FROM coupons
     WHERE code = COUPON_CODE
     AND is_active = true
     AND valid_from <= NOW()
     AND valid_until >= NOW()
     
     Validate:
     - Usage limits
     - Min order amount
     - Applicable suppliers
     - User usage count
     
     Calculate discount
   ↓
6. Calculate order totals
   subtotal = SUM(cart_items.item_total)
   delivery_fee = supplier.delivery_fee
   
   IF subtotal >= supplier.free_delivery_above THEN
     delivery_fee = 0
   
   service_fee = subtotal * 0.02 (2%)
   tax_amount = (subtotal + delivery_fee) * 0.18 (18% VAT)
   discount_amount = coupon_discount
   total_amount = subtotal + delivery_fee + service_fee + 
                  tax_amount - discount_amount
   ↓
7. Create order
   BEGIN TRANSACTION
   
   INSERT INTO orders (
     order_number = GENERATE_ORDER_NUMBER(),
     customer_id (or guest_session_id),
     supplier_id,
     service_type_id = 1, -- Instant delivery
     order_type = 'instant',
     order_status = 'pending',
     payment_method,
     payment_status = 'pending',
     delivery_address_id,
     delivery_address_text,
     delivery_latitude,
     delivery_longitude,
     delivery_phone,
     subtotal,
     delivery_fee,
     service_fee,
     tax_amount,
     discount_amount,
     coupon_code,
     total_amount,
     special_instructions,
     estimated_delivery_time = 45 (minutes)
   )
   ↓
8. Create order items
   FOR EACH cart_item:
     INSERT INTO order_items (
       order_id,
       menu_item_id,
       item_name,
       quantity,
       unit_price,
       variant_id,
       variant_name,
       selected_addons (JSON),
       addons_total,
       special_instructions,
       subtotal
     )
   ↓
9. Record coupon usage (if applied)
   INSERT INTO coupon_usage (
     coupon_id, user_id, order_id, discount_amount
   )
   
   UPDATE coupons SET times_used = times_used + 1
   ↓
10. Process payment
    IF payment_method = 'cash' THEN
      -- Order confirmed, payment on delivery
      payment_status = 'pending'
    
    ELSE IF payment_method IN ('card', 'mobile_money') THEN
      CALL PaymentGateway.initiate()
      
      INSERT INTO payments (
        payment_reference = GENERATE_REF(),
        order_id, user_id,
        payment_method, provider,
        amount, currency = 'TZS',
        status = 'processing'
      )
      
      IF payment_success THEN
        UPDATE payments SET
          status = 'completed',
          paid_at = NOW(),
          provider_transaction_id = TRANSACTION_ID
        
        UPDATE orders SET payment_status = 'paid'
      ELSE
        UPDATE payments SET
          status = 'failed',
          failure_reason = ERROR_MESSAGE
        
        ROLLBACK TRANSACTION
        RETURN payment_failed
   ↓
11. Record status history
    INSERT INTO order_status_history (
      order_id,
      old_status = NULL,
      new_status = 'pending',
      changed_at = NOW()
    )
   ↓
12. Clear cart
    IF logged_in THEN
      DELETE FROM user_carts WHERE user_id = USER_ID
    ELSE
      DELETE FROM guest_carts WHERE guest_session_id = SESSION_ID
   ↓
13. Update menu item metrics
    UPDATE menu_items SET
      order_count = order_count + quantity
    WHERE id IN (order_item_ids)
    
    Update stock (if applicable)
   ↓
14. Send notifications
    - Customer: Order confirmation
    - Supplier: New order notification
   ↓
   COMMIT TRANSACTION