<?php

// ============================================
// 1. USERS TABLE - Base authentication table
// ============================================
// Migration: 2024_01_01_000001_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('phone', 20)->unique();
            $table->string('password');
            $table->enum('user_type', ['customer', 'supplier', 'delivery_partner', 'admin'])->default('customer');
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending_verification'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('verification_code', 10)->nullable();
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['email', 'user_type']);
            $table->index('phone');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};

// ============================================
// 2. GUEST SESSIONS - For non-logged-in users
// ============================================
// Migration: 2024_01_01_000002_create_guest_sessions_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('guest_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_token', 64)->unique();
            $table->string('device_id', 100)->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent', 500)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location_address', 500)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->json('preferences')->nullable(); // Store cart, favorites, etc.
            $table->timestamp('last_activity_at');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index('session_token');
            $table->index(['latitude', 'longitude']);
            $table->index('last_activity_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_sessions');
    }
};

// ============================================
// 3. CUSTOMER PROFILES
// ============================================
// Migration: 2024_01_01_000003_create_customer_profiles_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            $table->string('profile_photo', 500)->nullable();
            $table->json('dietary_preferences')->nullable(); // ['vegetarian', 'vegan', 'halal', etc]
            $table->json('allergies')->nullable(); // ['nuts', 'dairy', etc]
            $table->string('default_payment_method', 50)->nullable();
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->integer('loyalty_points')->default(0);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_profiles');
    }
};

// ============================================
// 4. CUSTOMER ADDRESSES - Multiple addresses per customer
// ============================================
// Migration: 2024_01_01_000004_create_customer_addresses_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('guest_session_id')->nullable()->constrained('guest_sessions')->onDelete('cascade');
            $table->enum('address_type', ['home', 'work', 'other'])->default('home');
            $table->string('label', 50)->nullable(); // "Home", "Office", etc
            $table->string('address_line1', 255);
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100);
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20);
            $table->string('country', 100)->default('Tanzania');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('landmark', 255)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('delivery_instructions', 500)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            // Geospatial index for location-based queries
            $table->spatialIndex(['latitude', 'longitude']);
            $table->index('user_id');
            $table->index('guest_session_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_addresses');
    }
};

// ============================================
// 5. SERVICE TYPES - Instant, Daily Meals, Catering
// ============================================
// Migration: 2024_01_01_000005_create_service_types_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // "Instant Delivery", "Daily Meals", "Catering"
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('image', 500)->nullable();
            $table->json('features')->nullable(); // List of features for this service
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_types');
    }
};

// ============================================
// 6. CATEGORIES - Food categories/cuisines
// ============================================
// Migration: 2024_01_01_000006_create_categories_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('image', 500)->nullable();
            $table->string('icon', 255)->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->integer('display_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('parent_id');
            $table->index(['is_featured', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};

// ============================================
// 7. SUPPLIERS - Restaurants, Caterers, etc
// ============================================
// Migration: 2024_01_01_000007_create_suppliers_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name', 255);
            $table->string('slug', 255)->unique();
            $table->enum('business_type', ['restaurant', 'cafe', 'bakery', 'catering', 'cloud_kitchen', 'food_truck'])->default('restaurant');
            $table->text('description')->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->json('gallery_images')->nullable(); // Array of image URLs
            
            // Business verification
            $table->string('license_number', 100)->nullable();
            $table->string('tax_id', 100)->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            
            // Contact information
            $table->string('contact_email', 255);
            $table->string('contact_phone', 20);
            $table->string('website', 255)->nullable();
            
            // Operating details
            $table->json('operating_hours')->nullable(); // {monday: {open: "09:00", close: "22:00"}, ...}
            $table->integer('preparation_time')->default(30); // Average time in minutes
            $table->decimal('delivery_radius', 5, 2)->default(10.00); // in kilometers
            $table->decimal('min_order_amount', 10, 2)->default(0.00);
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            $table->decimal('free_delivery_above', 10, 2)->nullable();
            
            // Financial
            $table->decimal('commission_rate', 5, 2)->default(15.00); // Platform commission %
            $table->string('bank_account_name', 255)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_branch', 100)->nullable();
            $table->string('mobile_money_number', 20)->nullable();
            $table->enum('mobile_money_provider', ['mpesa', 'tigopesa', 'airtel_money', 'halopesa'])->nullable();
            
            // Performance metrics
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('acceptance_rate', 5, 2)->default(100.00);
            $table->decimal('cancellation_rate', 5, 2)->default(0.00);
            
            // Status flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_open_now')->default(false); // Real-time status
            $table->boolean('accepts_orders')->default(true);
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index(['is_active', 'is_featured']);
            $table->index('verification_status');
            $table->index('average_rating');
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};

// ============================================
// 8. SUPPLIER LOCATIONS - Physical locations
// ============================================
// Migration: 2024_01_01_000008_create_supplier_locations_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('supplier_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('location_name', 100)->nullable(); // "Main Branch", "Downtown"
            $table->string('address_line1', 255);
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100);
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20);
            $table->string('country', 100)->default('Tanzania');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('landmark', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Geospatial index for location queries
            $table->spatialIndex(['latitude', 'longitude']);
            $table->index('supplier_id');
            $table->index('is_primary');
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_locations');
    }
};

// ============================================
// 9. SUPPLIER SERVICE TYPES - Junction table
// ============================================
// Migration: 2024_01_01_000009_create_supplier_service_types_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('supplier_service_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained()->onDelete('cascade');
            $table->json('additional_info')->nullable(); // Service-specific settings
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['supplier_id', 'service_type_id']);
            $table->index('supplier_id');
            $table->index('service_type_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_service_types');
    }
};

// ============================================
// 10. SUPPLIER CATEGORIES - Junction table
// ============================================
// Migration: 2024_01_01_000010_create_supplier_categories_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('supplier_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['supplier_id', 'category_id']);
            $table->index('supplier_id');
            $table->index('category_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_categories');
    }
};

// ============================================
// 11. MENU CATEGORIES - For organizing menu items
// ============================================
// Migration: 2024_01_01_000011_create_menu_categories_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('category_name', 100);
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['supplier_id', 'display_order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_categories');
    }
};

// ============================================
// 12. MENU ITEMS - Food items
// ============================================
// Migration: 2024_01_01_000012_create_menu_items_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->json('gallery_images')->nullable();
            
            // Item properties
            $table->integer('preparation_time')->nullable(); // minutes
            $table->integer('serves')->nullable(); // number of people
            $table->string('portion_size', 50)->nullable(); // "Regular", "Large"
            $table->integer('calories')->nullable();
            
            // Dietary information
            $table->boolean('is_vegetarian')->default(false);
            $table->boolean('is_vegan')->default(false);
            $table->boolean('is_gluten_free')->default(false);
            $table->boolean('is_halal')->default(false);
            $table->boolean('is_spicy')->default(false);
            $table->json('allergens')->nullable(); // ['nuts', 'dairy', 'eggs']
            $table->json('ingredients')->nullable(); // List of ingredients
            
            // Availability
            $table->boolean('is_available')->default(true);
            $table->json('available_times')->nullable(); // Time slots when available
            $table->integer('stock_quantity')->nullable(); // For limited items
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            
            // Metrics
            $table->integer('view_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0.00);
            
            $table->integer('display_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['supplier_id', 'is_available']);
            $table->index('slug');
            $table->index(['is_featured', 'is_popular']);
            $table->index('order_count');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
};

// ============================================
// 13. MENU ITEM VARIANTS - Sizes, customizations
// ============================================
// Migration: 2024_01_01_000013_create_menu_item_variants_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->string('variant_name', 100); // "Small", "Medium", "Large"
            $table->decimal('price_adjustment', 10, 2)->default(0.00); // +/- from base price
            $table->boolean('is_available')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('menu_item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_item_variants');
    }
};

// ============================================
// 14. MENU ITEM ADDONS - Extra toppings, etc
// ============================================
// Migration: 2024_01_01_000014_create_menu_item_addons_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_item_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->string('addon_name', 100); // "Extra Cheese", "Bacon"
            $table->decimal('price', 10, 2);
            $table->boolean('is_available')->default(true);
            $table->integer('max_quantity')->default(1);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('menu_item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_item_addons');
    }
};

// ============================================
// 15. ORDERS - Main orders table
// ============================================
// Migration: 2024_01_01_000015_create_orders_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique(); // ORD-20240101-0001
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('guest_session_id')->nullable()->constrained('guest_sessions')->onDelete('set null');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained()->onDelete('restrict');
            
            // Order type
            $table->enum('order_type', ['instant', 'scheduled', 'catering', 'subscription'])->default('instant');
            
            // Order status workflow
            $table->enum('order_status', [
                'pending',           // Order placed, awaiting supplier acceptance
                'accepted',          // Supplier accepted
                'preparing',         // Food being prepared
                'ready',             // Ready for pickup
                'dispatched',        // Out for delivery
                'delivered',         // Successfully delivered
                'cancelled',         // Cancelled by customer/supplier
                'rejected',          // Rejected by supplier
                'failed'             // Failed delivery
            ])->default('pending');
            
            // Payment
            $table->enum('payment_method', ['cash', 'card', 'mobile_money', 'wallet'])->default('cash');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_reference', 100)->nullable();
            
            // Delivery address
            $table->foreignId('delivery_address_id')->nullable()->constrained('customer_addresses')->onDelete('set null');
            $table->string('delivery_address_text', 500); // Full address as text
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->string('delivery_phone', 20);
            $table->string('delivery_contact_name', 100)->nullable();
            
            // Timing
            $table->timestamp('scheduled_at')->nullable(); // For scheduled orders
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('prepared_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->integer('estimated_delivery_time')->nullable(); // minutes
            
            // Pricing
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            $table->decimal('service_fee', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->string('coupon_code', 50)->nullable();
            $table->decimal('total_amount', 10, 2);
            
            // Special instructions
            $table->text('special_instructions')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Delivery proof
            $table->string('delivery_otp', 6)->nullable();
            $table->string('delivery_photo', 500)->nullable();
            $table->text('delivery_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('order_number');
            $table->index(['customer_id', 'order_status']);
            $table->index(['supplier_id', 'order_status']);
            $table->index('guest_session_id');
            $table->index('order_type');
            $table->index('created_at');
            $table->spatialIndex(['delivery_latitude', 'delivery_longitude']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};

// ============================================
// 16. ORDER ITEMS - Items in an order
// ============================================
// Migration: 2024_01_01_000016_create_order_items_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name', 255); // Store name in case item is deleted
            $table->text('item_description')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->foreignId('variant_id')->nullable()->constrained('menu_item_variants')->onDelete('set null');
            $table->string('variant_name', 100)->nullable();
            $table->json('selected_addons')->nullable(); // [{id, name, price, quantity}]
            $table->decimal('addons_total', 10, 2)->default(0.00);
            $table->text('special_instructions')->nullable();
            $table->decimal('subtotal', 10, 2); // (unit_price + addons) * quantity
            $table->timestamps();

            $table->index('order_id');
            $table->index('menu_item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};

// ============================================
// 17. ORDER STATUS HISTORY - Track status changes
// ============================================
// Migration: 2024_01_01_000017_create_order_status_history_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->index(['order_id', 'changed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_status_history');
    }
};

// ============================================
// 18. GUEST CARTS - Shopping cart for guests
// ============================================
// Migration: 2024_01_01_000018_create_guest_carts_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('guest_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_session_id')->constrained('guest_sessions')->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->foreignId('variant_id')->nullable()->constrained('menu_item_variants')->onDelete('set null');
            $table->json('selected_addons')->nullable(); // [{id, name, price, quantity}]
            $table->text('special_instructions')->nullable();
            $table->decimal('item_total', 10, 2);
            $table->timestamps();

            $table->index('guest_session_id');
            $table->index(['guest_session_id', 'supplier_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_carts');
    }
};

// ============================================
// 19. USER CARTS - Shopping cart for logged-in users
// ============================================
// Migration: 2024_01_01_000019_create_user_carts_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->foreignId('variant_id')->nullable()->constrained('menu_item_variants')->onDelete('set null');
            $table->json('selected_addons')->nullable();
            $table->text('special_instructions')->nullable();
            $table->decimal('item_total', 10, 2);
            $table->timestamps();

            $table->index('user_id');
            $table->index(['user_id', 'supplier_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_carts');
    }
};

// ============================================
// 20. CATERING REQUESTS
// ============================================
// Migration: 2024_01_01_000020_create_catering_requests_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('catering_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number', 50)->unique();
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('guest_session_id')->nullable()->constrained('guest_sessions')->onDelete('set null');
            
            // Event details
            $table->string('event_type', 100); // Wedding, Corporate, Birthday, etc
            $table->date('event_date');
            $table->time('event_time');
            $table->integer('duration_hours')->nullable();
            $table->integer('guest_count');
            $table->string('venue_name', 255)->nullable();
            $table->string('venue_address', 500);
            $table->decimal('venue_latitude', 10, 8)->nullable();
            $table->decimal('venue_longitude', 11, 8)->nullable();
            
            // Service requirements
            $table->enum('service_type', ['buffet', 'plated', 'cocktail', 'family_style'])->nullable();
            $table->json('cuisine_preferences')->nullable();
            $table->json('dietary_requirements')->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->text('additional_requirements')->nullable();
            
            // Contact
            $table->string('contact_name', 100);
            $table->string('contact_email', 255);
            $table->string('contact_phone', 20);
            
            // Status
            $table->enum('status', ['pending', 'quoted', 'accepted', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'status']);
            $table->index('event_date');
            $table->spatialIndex(['venue_latitude', 'venue_longitude']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('catering_requests');
    }
};

// ============================================
// 21. CATERING PROPOSALS - Supplier quotes
// ============================================
// Migration: 2024_01_01_000021_create_catering_proposals_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('catering_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catering_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('proposal_number', 50)->unique();
            
            // Proposal details
            $table->json('menu_items')->nullable(); // Proposed menu with items
            $table->decimal('price_per_person', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2);
            $table->decimal('setup_fee', 10, 2)->default(0.00);
            $table->decimal('service_fee', 10, 2)->default(0.00);
            $table->text('inclusions')->nullable(); // What's included
            $table->text('exclusions')->nullable(); // What's not included
            $table->text('terms_and_conditions')->nullable();
            
            // Additional services
            $table->boolean('includes_setup')->default(false);
            $table->boolean('includes_service_staff')->default(false);
            $table->boolean('includes_equipment')->default(false);
            $table->boolean('includes_decoration')->default(false);
            $table->integer('staff_count')->nullable();
            
            // Validity
            $table->date('valid_until');
            $table->text('notes')->nullable();
            
            // Status
            $table->enum('status', ['submitted', 'viewed', 'accepted', 'rejected', 'expired'])->default('submitted');
            $table->timestamp('submitted_at');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();

            $table->index(['catering_request_id', 'status']);
            $table->index('supplier_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('catering_proposals');
    }
};

// ============================================
// 22. SUBSCRIPTIONS - Daily/Weekly meal plans
// ============================================
// Migration: 2024_01_01_000022_create_subscriptions_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_number', 50)->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            
            // Plan details
            $table->enum('plan_type', ['daily', 'weekly', 'monthly'])->default('daily');
            $table->json('meal_times')->nullable(); // ['breakfast', 'lunch', 'dinner']
            $table->integer('meals_per_day')->default(1);
            $table->json('dietary_preferences')->nullable();
            
            // Delivery
            $table->foreignId('delivery_address_id')->constrained('customer_addresses')->onDelete('restrict');
            $table->json('delivery_schedule')->nullable(); // {monday: true, tuesday: false, ...}
            $table->time('preferred_delivery_time')->nullable();
            
            // Pricing
            $table->decimal('price_per_meal', 10, 2);
            $table->decimal('price_per_period', 10, 2); // Total for the period
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            
            // Duration
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('next_billing_date')->nullable();
            $table->integer('billing_cycle_days')->default(7); // Weekly by default
            
            // Status
            $table->enum('status', ['active', 'paused', 'cancelled', 'completed', 'pending'])->default('pending');
            $table->boolean('auto_renew')->default(true);
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['supplier_id', 'status']);
            $table->index('start_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};

// ============================================
// 23. SUBSCRIPTION DELIVERIES - Individual deliveries
// ============================================
// Migration: 2024_01_01_000023_create_subscription_deliveries_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->date('delivery_date');
            $table->enum('meal_time', ['breakfast', 'lunch', 'dinner']);
            $table->enum('status', ['scheduled', 'skipped', 'delivered', 'failed'])->default('scheduled');
            $table->timestamp('delivered_at')->nullable();
            $table->text('skip_reason')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->timestamps();

            $table->index(['subscription_id', 'delivery_date']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_deliveries');
    }
};

// ============================================
// 24. DELIVERY PARTNERS
// ============================================
// Migration: 2024_01_01_000024_create_delivery_partners_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('profile_photo', 500)->nullable();
            $table->string('phone', 20);
            $table->string('emergency_contact', 20)->nullable();
            
            // Vehicle information
            $table->enum('vehicle_type', ['bicycle', 'motorcycle', 'car', 'scooter'])->default('motorcycle');
            $table->string('vehicle_make', 100)->nullable();
            $table->string('vehicle_model', 100)->nullable();
            $table->string('vehicle_plate', 50)->nullable();
            $table->string('vehicle_color', 50)->nullable();
            
            // Documents
            $table->string('license_number', 100);
            $table->date('license_expiry');
            $table->string('license_photo', 500)->nullable();
            $table->string('id_number', 100);
            $table->string('id_photo', 500)->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            
            // Bank details
            $table->string('bank_account_name', 255)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('mobile_money_number', 20)->nullable();
            
            // Location & status
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('location_updated_at')->nullable();
            $table->enum('availability_status', ['available', 'busy', 'offline'])->default('offline');
            $table->boolean('is_online')->default(false);
            
            // Performance
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('total_deliveries')->default(0);
            $table->decimal('completion_rate', 5, 2)->default(100.00);
            $table->decimal('on_time_rate', 5, 2)->default(100.00);
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_online', 'availability_status']);
            $table->spatialIndex(['current_latitude', 'current_longitude']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_partners');
    }
};

// ============================================
// 25. DELIVERIES - Delivery assignments
// ============================================
// Migration: 2024_01_01_000025_create_deliveries_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('delivery_partner_id')->nullable()->constrained()->onDelete('set null');
            
            // Pickup details
            $table->foreignId('pickup_location_id')->constrained('supplier_locations')->onDelete('restrict');
            $table->decimal('pickup_latitude', 10, 8);
            $table->decimal('pickup_longitude', 11, 8);
            $table->timestamp('pickup_time')->nullable();
            $table->string('pickup_otp', 6)->nullable();
            
            // Delivery details
            $table->decimal('delivery_latitude', 10, 8);
            $table->decimal('delivery_longitude', 11, 8);
            $table->timestamp('delivery_time')->nullable();
            $table->string('delivery_otp', 6)->nullable();
            $table->string('delivery_photo', 500)->nullable();
            $table->text('delivery_notes')->nullable();
            
            // Distance & timing
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->integer('estimated_time_minutes')->nullable();
            $table->integer('actual_time_minutes')->nullable();
            
            // Status
            $table->enum('status', [
                'assigned',
                'accepted',
                'rejected',
                'arrived_at_pickup',
                'picked_up',
                'on_the_way',
                'arrived_at_delivery',
                'delivered',
                'failed'
            ])->default('assigned');
            
            // Partner earnings
            $table->decimal('delivery_fee', 10, 2);
            $table->decimal('partner_earnings', 10, 2);
            $table->decimal('platform_commission', 10, 2);
            
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
            $table->index(['delivery_partner_id', 'status']);
            $table->spatialIndex(['pickup_latitude', 'pickup_longitude']);
            $table->spatialIndex(['delivery_latitude', 'delivery_longitude']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
};

// ============================================
// 26. REVIEWS & RATINGS
// ============================================
// Migration: 2024_01_01_000026_create_reviews_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('delivery_partner_id')->nullable()->constrained()->onDelete('set null');
            
            // Ratings (1-5 scale)
            $table->integer('food_rating')->nullable();
            $table->integer('service_rating')->nullable();
            $table->integer('delivery_rating')->nullable();
            $table->decimal('overall_rating', 3, 2);
            
            // Review text
            $table->text('review_text')->nullable();
            $table->json('tags')->nullable(); // ['Great taste', 'Fast delivery']
            $table->json('images')->nullable(); // Review photos
            
            // Response
            $table->text('supplier_response')->nullable();
            $table->timestamp('response_at')->nullable();
            
            // Moderation
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            
            $table->timestamps();

            $table->index(['supplier_id', 'is_approved']);
            $table->index(['customer_id', 'created_at']);
            $table->index('overall_rating');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};

// ============================================
// 27. COUPONS & PROMOTIONS
// ============================================
// Migration: 2024_01_01_000027_create_coupons_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable();
            
            // Discount
            $table->enum('discount_type', ['percentage', 'fixed', 'free_delivery'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->decimal('min_order_amount', 10, 2)->default(0.00);
            
            // Applicability
            $table->enum('applicable_to', ['all', 'specific_suppliers', 'specific_categories', 'first_order'])->default('all');
            $table->json('supplier_ids')->nullable();
            $table->json('category_ids')->nullable();
            
            // Usage limits
            $table->integer('usage_limit')->nullable(); // Total uses
            $table->integer('usage_limit_per_user')->default(1);
            $table->integer('times_used')->default(0);
            
            // Validity
            $table->timestamp('valid_from');
            $table->timestamp('valid_until');
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();

            $table->index('code');
            $table->index(['is_active', 'valid_from', 'valid_until']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};

// ============================================
// 28. COUPON USAGE - Track who used coupons
// ============================================
// Migration: 2024_01_01_000028_create_coupon_usage_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();

            $table->index(['coupon_id', 'user_id']);
            $table->index('order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_usage');
    }
};

// ============================================
// 29. PAYMENTS
// ============================================
// Migration: 2024_01_01_000029_create_payments_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_reference', 100)->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Payment details
            $table->enum('payment_method', ['cash', 'card', 'mobile_money', 'wallet'])->default('cash');
            $table->string('provider', 100)->nullable(); // Stripe, M-Pesa, etc
            $table->string('provider_transaction_id', 255)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('TZS');
            
            // Status
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->text('failure_reason')->nullable();
            
            // Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            
            // Additional data
            $table->json('metadata')->nullable();
            
            $table->timestamps();

            $table->index(['order_id', 'status']);
            $table->index('payment_reference');
            $table->index('provider_transaction_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};

// ============================================
// 30. SUPPLIER PAYOUTS
// ============================================
// Migration: 2024_01_01_000030_create_supplier_payouts_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('supplier_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_reference', 100)->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            
            // Period
            $table->date('period_start');
            $table->date('period_end');
            
            // Financials
            $table->integer('total_orders');
            $table->decimal('gross_amount', 10, 2); // Total order amounts
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('delivery_fees', 10, 2)->default(0.00);
            $table->decimal('refunds', 10, 2)->default(0.00);
            $table->decimal('adjustments', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2); // Amount to be paid
            $table->string('currency', 10)->default('TZS');
            
            // Payment details
            $table->enum('payment_method', ['bank_transfer', 'mobile_money'])->default('bank_transfer');
            $table->string('payment_reference', 255)->nullable();
            
            // Status
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();

            $table->index(['supplier_id', 'status']);
            $table->index(['period_start', 'period_end']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_payouts');
    }
};

// ============================================
// 31. NOTIFICATIONS
// ============================================
// Migration: 2024_01_01_000031_create_notifications_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type', 100); // order_status, promotion, etc
            $table->string('title', 255);
            $table->text('message');
            $table->json('data')->nullable(); // Additional data
            $table->enum('channel', ['push', 'email', 'sms', 'in_app'])->default('in_app');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};

// ============================================
// 32. BANNERS & PROMOTIONS
// ============================================
// Migration: 2024_01_01_000032_create_banners_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('image_url', 500);
            $table->string('mobile_image_url', 500)->nullable();
            $table->enum('banner_type', ['home_slider', 'category', 'promotional'])->default('home_slider');
            $table->string('cta_text', 100)->nullable(); // Call to action
            $table->string('cta_url', 500)->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('is_active')->default(true);
            $table->integer('click_count')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'start_date', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
};

// ============================================
// 33. FAVORITES/WISHLIST
// ============================================
// Migration: 2024_01_01_000033_create_favorites_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('favorable'); // Can favorite suppliers or menu items
            $table->timestamps();

            $table->unique(['user_id', 'favorable_type', 'favorable_id']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};

// ============================================
// 34. APP SETTINGS - Platform configuration
// ============================================
// Migration: 2024_01_01_000034_create_app_settings_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('data_type', 50)->default('string'); // string, integer, boolean, json
            $table->string('group', 100)->default('general'); // general, payment, delivery, etc
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('key');
            $table->index('group');
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_settings');
    }
};