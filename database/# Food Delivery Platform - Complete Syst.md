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