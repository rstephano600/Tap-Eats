<?php

// ============================================
// 1. USER MODEL
// ============================================
// File: app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'email',
        'phone',
        'password',
        'user_type',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'verification_code',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    // Relationships
    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    public function deliveryPartner()
    {
        return $this->hasOne(DeliveryPartner::class);
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function cart()
    {
        return $this->hasMany(UserCart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'customer_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopeCustomers($query)
    {
        return $query->where('user_type', 'customer');
    }

    public function scopeSuppliers($query)
    {
        return $query->where('user_type', 'supplier');
    }

    public function scopeDeliveryPartners($query)
    {
        return $query->where('user_type', 'delivery_partner');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at')
                    ->whereNotNull('phone_verified_at');
    }

    // Helper methods
    public function isCustomer()
    {
        return $this->user_type === 'customer';
    }

    public function isSupplier()
    {
        return $this->user_type === 'supplier';
    }

    public function isDeliveryPartner()
    {
        return $this->user_type === 'delivery_partner';
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function isVerified()
    {
        return !is_null($this->email_verified_at) && !is_null($this->phone_verified_at);
    }
}

// ============================================
// 2. GUEST SESSION MODEL
// ============================================
// File: app/Models/GuestSession.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GuestSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'device_id',
        'ip_address',
        'user_agent',
        'latitude',
        'longitude',
        'location_address',
        'city',
        'country',
        'preferences',
        'last_activity_at',
        'expires_at',
    ];

    protected $casts = [
        'preferences' => 'array',
        'last_activity_at' => 'datetime',
        'expires_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function cart()
    {
        return $this->hasMany(GuestCart::class);
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeInactive($query, $minutes = 30)
    {
        return $query->where('last_activity_at', '<', now()->subMinutes($minutes));
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function updateActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }

    public static function generateToken()
    {
        return Str::random(64);
    }

    public function extendExpiry($hours = 24)
    {
        $this->update(['expires_at' => now()->addHours($hours)]);
    }
}

// ============================================
// 3. CUSTOMER PROFILE MODEL
// ============================================
// File: app/Models/CustomerProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'profile_photo',
        'dietary_preferences',
        'allergies',
        'default_payment_method',
        'email_notifications',
        'sms_notifications',
        'push_notifications',
        'loyalty_points',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'dietary_preferences' => 'array',
        'allergies' => 'array',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'push_notifications' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Helper methods
    public function addLoyaltyPoints($points)
    {
        $this->increment('loyalty_points', $points);
    }

    public function deductLoyaltyPoints($points)
    {
        if ($this->loyalty_points >= $points) {
            $this->decrement('loyalty_points', $points);
            return true;
        }
        return false;
    }
}

// ============================================
// 4. CUSTOMER ADDRESS MODEL
// ============================================
// File: app/Models/CustomerAddress.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_session_id',
        'address_type',
        'label',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'landmark',
        'contact_phone',
        'delivery_instructions',
        'is_default',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_default' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guestSession()
    {
        return $this->belongsTo(GuestSession::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_address_id');
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForGuest($query, $guestSessionId)
    {
        return $query->where('guest_session_id', $guestSessionId);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]));
    }

    // Helper methods
    public function setAsDefault()
    {
        // Remove default from other addresses
        if ($this->user_id) {
            self::where('user_id', $this->user_id)
                ->where('id', '!=', $this->id)
                ->update(['is_default' => false]);
        }
        
        $this->update(['is_default' => true]);
    }

    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Haversine formula
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in km
    }
}

// ============================================
// 5. SERVICE TYPE MODEL
// ============================================
// File: app/Models/ServiceType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'features',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_service_types')
                    ->withPivot('additional_info', 'is_active')
                    ->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}

// ============================================
// 6. CATEGORY MODEL
// ============================================
// File: app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'slug',
        'description',
        'image',
        'icon',
        'parent_id',
        'display_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_categories')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    // Accessors
    public function getSupplierCountAttribute()
    {
        return $this->suppliers()->count();
    }
}

// ============================================
// 7. SUPPLIER MODEL
// ============================================
// File: app/Models/Supplier.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_name',
        'slug',
        'business_type',
        'description',
        'logo_url',
        'cover_image',
        'gallery_images',
        'license_number',
        'tax_id',
        'verification_status',
        'verified_at',
        'contact_email',
        'contact_phone',
        'website',
        'operating_hours',
        'preparation_time',
        'delivery_radius',
        'min_order_amount',
        'delivery_fee',
        'free_delivery_above',
        'commission_rate',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'bank_branch',
        'mobile_money_number',
        'mobile_money_provider',
        'average_rating',
        'total_reviews',
        'total_orders',
        'acceptance_rate',
        'cancellation_rate',
        'is_active',
        'is_featured',
        'is_open_now',
        'accepts_orders',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'operating_hours' => 'array',
        'verified_at' => 'datetime',
        'delivery_radius' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'free_delivery_above' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'acceptance_rate' => 'decimal:2',
        'cancellation_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_open_now' => 'boolean',
        'accepts_orders' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function locations()
    {
        return $this->hasMany(SupplierLocation::class);
    }

    public function primaryLocation()
    {
        return $this->hasOne(SupplierLocation::class)->where('is_primary', true);
    }

    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class, 'supplier_service_types')
                    ->withPivot('additional_info', 'is_active')
                    ->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'supplier_categories')
                    ->withTimestamps();
    }

    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payouts()
    {
        return $this->hasMany(SupplierPayout::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function cateringProposals()
    {
        return $this->hasMany(CateringProposal::class);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeOpen($query)
    {
        return $query->where('is_open_now', true)
                    ->where('accepts_orders', true);
    }

    public function scopeAcceptingOrders($query)
    {
        return $query->where('accepts_orders', true);
    }

    public function scopeWithinRadius($query, $latitude, $longitude, $radiusKm = 10)
    {
        // Using Haversine formula for distance calculation
        return $query->select('suppliers.*')
            ->join('supplier_locations', 'suppliers.id', '=', 'supplier_locations.supplier_id')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) *
                cos( radians( supplier_locations.latitude ) ) *
                cos( radians( supplier_locations.longitude ) - radians(?) ) +
                sin( radians(?) ) *
                sin( radians( supplier_locations.latitude ) ) ) ) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->havingRaw('distance < ?', [$radiusKm])
            ->orderBy('distance');
    }

    public function scopeHighRated($query, $minRating = 4.0)
    {
        return $query->where('average_rating', '>=', $minRating);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    public function scopeByServiceType($query, $serviceTypeId)
    {
        return $query->whereHas('serviceTypes', function ($q) use ($serviceTypeId) {
            $q->where('service_types.id', $serviceTypeId);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('business_name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('categories', function ($q) use ($search) {
                  $q->where('category_name', 'like', "%{$search}%");
              });
        });
    }

    // Helper methods
    public function isOpen()
    {
        return $this->is_open_now && $this->accepts_orders;
    }

    public function updateRating()
    {
        $stats = $this->reviews()
            ->selectRaw('AVG(overall_rating) as avg_rating, COUNT(*) as total')
            ->first();

        $this->update([
            'average_rating' => $stats->avg_rating ?? 0,
            'total_reviews' => $stats->total ?? 0,
        ]);
    }

    public function canDeliver($latitude, $longitude)
    {
        $location = $this->primaryLocation;
        
        if (!$location) {
            return false;
        }

        $distance = CustomerAddress::calculateDistance(
            $location->latitude,
            $location->longitude,
            $latitude,
            $longitude
        );

        return $distance <= $this->delivery_radius;
    }

    public function calculateDeliveryFee($orderAmount)
    {
        if ($this->free_delivery_above && $orderAmount >= $this->free_delivery_above) {
            return 0;
        }

        return $this->delivery_fee;
    }
}

// ============================================
// 8. SUPPLIER LOCATION MODEL
// ============================================
// File: app/Models/SupplierLocation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'location_name',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'landmark',
        'phone',
        'is_primary',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'pickup_location_id');
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]));
    }

    // Helper methods
    public function setAsPrimary()
    {
        // Remove primary from other locations
        self::where('supplier_id', $this->supplier_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);
        
        $this->update(['is_primary' => true]);
    }
}

// ============================================
// 9. MENU CATEGORY MODEL
// ============================================
// File: app/Models/MenuCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'category_name',
        'description',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function scopeWithAvailableItems($query)
    {
        return $query->whereHas('menuItems', function ($q) {
            $q->where('is_available', true);
        });
    }
}

// ============================================
// 10. MENU ITEM MODEL
// ============================================
// File: app/Models/MenuItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'menu_category_id',
        'name',
        'slug',
        'description',
        'price',
        'discounted_price',
        'image_url',
        'gallery_images',
        'preparation_time',
        'serves',
        'portion_size',
        'calories',
        'is_vegetarian',
        'is_vegan',
        'is_gluten_free',
        'is_halal',
        'is_spicy',
        'allergens',
        'ingredients',
        'is_available',
        'available_times',
        'stock_quantity',
        'is_featured',
        'is_popular',
        'view_count',
        'order_count',
        'average_rating',
        'display_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'gallery_images' => 'array',
        'allergens' => 'array',
        'ingredients' => 'array',
        'available_times' => 'array',
        'is_vegetarian' => 'boolean',
        'is_vegan' => 'boolean',
        'is_gluten_free' => 'boolean',
        'is_halal' => 'boolean',
        'is_spicy' => 'boolean',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'average_rating' => 'decimal:2',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function variants()
    {
        return $this->hasMany(MenuItemVariant::class);
    }

    public function addons()
    {
        return $this->hasMany(MenuItemAddon::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeVegetarian($query)
    {
        return $query->where('is_vegetarian', true);
    }

    public function scopeVegan($query)
    {
        return $query->where('is_vegan', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('stock_quantity')
              ->orWhere('stock_quantity', '>', 0);
        });
    }

    // Accessors
    public function getCurrentPriceAttribute()
    {
        return $this->discounted_price ?? $this->price;
    }

    public function getHasDiscountAttribute()
    {
        return !is_null($this->discounted_price) && $this->discounted_price < $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) {
            return 0;
        }

        return round((($this->price - $this->discounted_price) / $this->price) * 100);
    }

    // Helper methods
    public function incrementViews()
    {
        $this->increment('view_count');
    }

    public function incrementOrders()
    {
        $this->increment('order_count');
    }

    public function decrementStock($quantity = 1)
    {
        if ($this->stock_quantity !== null) {
            $this->decrement('stock_quantity', $quantity);
            
            if ($this->stock_quantity <= 0) {
                $this->update(['is_available' => false]);
            }
        }
    }

    public function restockItem($quantity)
    {
        if ($this->stock_quantity !== null) {
            $this->increment('stock_quantity', $quantity);
            $this->update(['is_available' => true]);
        }
    }
}

// ============================================
// 11. MENU ITEM VARIANT MODEL
// ============================================
// File: app/Models/MenuItemVariant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'variant_name',
        'price_adjustment',
        'is_available',
        'display_order',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    // Relationships
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}

// ============================================
// 12. MENU ITEM ADDON MODEL
// ============================================
// File: app/Models/MenuItemAddon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'addon_name',
        'price',
        'is_available',
        'max_quantity',
        'display_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    // Relationships
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}

// ============================================
// 13. ORDER MODEL
// ============================================
// File: app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'guest_session_id',
        'supplier_id',
        'service_type_id',
        'order_type',
        'order_status',
        'payment_method',
        'payment_status',
        'payment_reference',
        'delivery_address_id',
        'delivery_address_text',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_phone',
        'delivery_contact_name',
        'scheduled_at',
        'accepted_at',
        'prepared_at',
        'dispatched_at',
        'delivered_at',
        'cancelled_at',
        'estimated_delivery_time',
        'subtotal',
        'delivery_fee',
        'service_fee',
        'tax_amount',
        'discount_amount',
        'coupon_code',
        'total_amount',
        'special_instructions',
        'cancellation_reason',
        'rejection_reason',
        'delivery_otp',
        'delivery_photo',
        'delivery_notes',
    ];

    protected $casts = [
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'scheduled_at' => 'datetime',
        'accepted_at' => 'datetime',
        'prepared_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function guestSession()
    {
        return $this->belongsTo(GuestSession::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(CustomerAddress::class, 'delivery_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function couponUsage()
    {
        return $this->hasOne(CouponUsage::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('order_status', 'accepted');
    }

    public function scopePreparing($query)
    {
        return $query->where('order_status', 'preparing');
    }

    public function scopeReady($query)
    {
        return $query->where('order_status', 'ready');
    }

    public function scopeDispatched($query)
    {
        return $query->where('order_status', 'dispatched');
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('order_status', 'cancelled');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('order_status', ['pending', 'accepted', 'preparing', 'ready', 'dispatched']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('order_status', 'delivered');
    }

    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeForSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    public function scopeScheduled($query)
    {
        return $query->where('order_type', 'scheduled')
                    ->whereNotNull('scheduled_at');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Helper methods
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastOrder ? intval(substr($lastOrder->order_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }

    public function updateStatus($newStatus, $userId = null, $notes = null)
    {
        $oldStatus = $this->order_status;
        
        $this->update(['order_status' => $newStatus]);
        
        // Record status change
        $this->statusHistory()->create([
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $userId,
            'notes' => $notes,
            'changed_at' => now(),
        ]);

        // Update timestamps
        $timestampField = $this->getTimestampFieldForStatus($newStatus);
        if ($timestampField) {
            $this->update([$timestampField => now()]);
        }
    }

    protected function getTimestampFieldForStatus($status)
    {
        $statusTimestamps = [
            'accepted' => 'accepted_at',
            'preparing' => 'prepared_at',
            'dispatched' => 'dispatched_at',
            'delivered' => 'delivered_at',
            'cancelled' => 'cancelled_at',
        ];

        return $statusTimestamps[$status] ?? null;
    }

    public function canBeCancelled()
    {
        return in_array($this->order_status, ['pending', 'accepted']);
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isActive()
    {
        return in_array($this->order_status, ['pending', 'accepted', 'preparing', 'ready', 'dispatched']);
    }

    public function isCompleted()
    {
        return $this->order_status === 'delivered';
    }

    public function generateDeliveryOTP()
    {
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update(['delivery_otp' => $otp]);
        return $otp;
    }

    public function verifyDeliveryOTP($otp)
    {
        return $this->delivery_otp === $otp;
    }
}

// ============================================
// 14. ORDER ITEM MODEL
// ============================================
// File: app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'item_name',
        'item_description',
        'quantity',
        'unit_price',
        'variant_id',
        'variant_name',
        'selected_addons',
        'addons_total',
        'special_instructions',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'addons_total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'selected_addons' => 'array',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function variant()
    {
        return $this->belongsTo(MenuItemVariant::class);
    }

    // Accessors
    public function getTotalPriceAttribute()
    {
        return ($this->unit_price + $this->addons_total) * $this->quantity;
    }
}

// ============================================
// 15. ORDER STATUS HISTORY MODEL
// ============================================
// File: app/Models/OrderStatusHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'order_status_history';

    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

// ============================================
// 16. CART MODELS (Guest & User)
// ============================================
// File: app/Models/GuestCart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_session_id',
        'menu_item_id',
        'supplier_id',
        'quantity',
        'variant_id',
        'selected_addons',
        'special_instructions',
        'item_total',
    ];

    protected $casts = [
        'selected_addons' => 'array',
        'item_total' => 'decimal:2',
    ];

    // Relationships
    public function guestSession()
    {
        return $this->belongsTo(GuestSession::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function variant()
    {
        return $this->belongsTo(MenuItemVariant::class);
    }

    // Helper methods
    public function calculateTotal()
    {
        $basePrice = $this->menuItem->current_price;
        
        if ($this->variant_id) {
            $basePrice += $this->variant->price_adjustment;
        }

        $addonsTotal = 0;
        if ($this->selected_addons) {
            foreach ($this->selected_addons as $addon) {
                $addonsTotal += $addon['price'] * ($addon['quantity'] ?? 1);
            }
        }

        $this->item_total = ($basePrice + $addonsTotal) * $this->quantity;
        $this->save();

        return $this->item_total;
    }
}

// File: app/Models/UserCart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu_item_id',
        'supplier_id',
        'quantity',
        'variant_id',
        'selected_addons',
        'special_instructions',
        'item_total',
    ];

    protected $casts = [
        'selected_addons' => 'array',
        'item_total' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function variant()
    {
        return $this->belongsTo(MenuItemVariant::class);
    }

    // Scopes
    public function scopeForSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    // Helper methods
    public function calculateTotal()
    {
        $basePrice = $this->menuItem->current_price;
        
        if ($this->variant_id) {
            $basePrice += $this->variant->price_adjustment;
        }

        $addonsTotal = 0;
        if ($this->selected_addons) {
            foreach ($this->selected_addons as $addon) {
                $addonsTotal += $addon['price'] * ($addon['quantity'] ?? 1);
            }
        }

        $this->item_total = ($basePrice + $addonsTotal) * $this->quantity;
        $this->save();

        return $this->item_total;
    }
}

// ============================================
// 17. CATERING REQUEST MODEL
// ============================================
// File: app/Models/CateringRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CateringRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_number',
        'customer_id',
        'guest_session_id',
        'event_type',
        'event_date',
        'event_time',
        'duration_hours',
        'guest_count',
        'venue_name',
        'venue_address',
        'venue_latitude',
        'venue_longitude',
        'service_type',
        'cuisine_preferences',
        'dietary_requirements',
        'budget_min',
        'budget_max',
        'additional_requirements',
        'contact_name',
        'contact_email',
        'contact_phone',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'cuisine_preferences' => 'array',
        'dietary_requirements' => 'array',
        'venue_latitude' => 'decimal:8',
        'venue_longitude' => 'decimal:8',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function guestSession()
    {
        return $this->belongsTo(GuestSession::class);
    }

    public function proposals()
    {
        return $this->hasMany(CateringProposal::class);
    }

    public function acceptedProposal()
    {
        return $this->hasOne(CateringProposal::class)->where('status', 'accepted');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', today());
    }

    // Helper methods
    public static function generateRequestNumber()
    {
        $prefix = 'CTR';
        $date = now()->format('Ymd');
        $lastRequest = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastRequest ? intval(substr($lastRequest->request_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }
}

// ============================================
// 18. CATERING PROPOSAL MODEL
// ============================================
// File: app/Models/CateringProposal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'catering_request_id',
        'supplier_id',
        'proposal_number',
        'menu_items',
        'price_per_person',
        'total_price',
        'setup_fee',
        'service_fee',
        'inclusions',
        'exclusions',
        'terms_and_conditions',
        'includes_setup',
        'includes_service_staff',
        'includes_equipment',
        'includes_decoration',
        'staff_count',
        'valid_until',
        'notes',
        'status',
        'submitted_at',
        'viewed_at',
        'accepted_at',
        'rejection_reason',
    ];

    protected $casts = [
        'menu_items' => 'array',
        'price_per_person' => 'decimal:2',
        'total_price' => 'decimal:2',
        'setup_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'includes_setup' => 'boolean',
        'includes_service_staff' => 'boolean',
        'includes_equipment' => 'boolean',
        'includes_decoration' => 'boolean',
        'valid_until' => 'date',
        'submitted_at' => 'datetime',
        'viewed_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    // Relationships
    public function cateringRequest()
    {
        return $this->belongsTo(CateringRequest::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Scopes
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeValid($query)
    {
        return $query->where('valid_until', '>=', today())
                    ->where('status', 'submitted');
    }

    // Helper methods
    public static function generateProposalNumber()
    {
        $prefix = 'CTP';
        $date = now()->format('Ymd');
        $lastProposal = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastProposal ? intval(substr($lastProposal->proposal_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }

    public function isExpired()
    {
        return $this->valid_until->isPast();
    }

    public function markAsViewed()
    {
        if (!$this->viewed_at) {
            $this->update([
                'viewed_at' => now(),
                'status' => 'viewed',
            ]);
        }
    }
}

// ============================================
// 19. SUBSCRIPTION MODEL
// ============================================
// File: app/Models/Subscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_number',
        'customer_id',
        'supplier_id',
        'plan_type',
        'meal_times',
        'meals_per_day',
        'dietary_preferences',
        'delivery_address_id',
        'delivery_schedule',
        'preferred_delivery_time',
        'price_per_meal',
        'price_per_period',
        'delivery_fee',
        'start_date',
        'end_date',
        'next_billing_date',
        'billing_cycle_days',
        'status',
        'auto_renew',
        'cancellation_reason',
        'paused_at',
        'cancelled_at',
    ];

    protected $casts = [
        'meal_times' => 'array',
        'dietary_preferences' => 'array',
        'delivery_schedule' => 'array',
        'price_per_meal' => 'decimal:2',
        'price_per_period' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'next_billing_date' => 'date',
        'auto_renew' => 'boolean',
        'paused_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(CustomerAddress::class);
    }

    public function deliveries()
    {
        return $this->hasMany(SubscriptionDelivery::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePaused($query)
    {
        return $query->where('status', 'paused');
    }

    public function scopeDueForBilling($query)
    {
        return $query->where('status', 'active')
                    ->where('next_billing_date', '<=', today());
    }

    // Helper methods
    public static function generateSubscriptionNumber()
    {
        $prefix = 'SUB';
        $date = now()->format('Ymd');
        $lastSub = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastSub ? intval(substr($lastSub->subscription_number, -4)) + 1 : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }

    public function pause()
    {
        $this->update([
            'status' => 'paused',
            'paused_at' => now(),
        ]);
    }

    public function resume()
    {
        $this->update([
            'status' => 'active',
            'paused_at' => null,
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);
    }
}

// ============================================
// 20. SUBSCRIPTION DELIVERY MODEL
// ============================================
// File: app/Models/SubscriptionDelivery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'order_id',
        'delivery_date',
        'meal_time',
        'status',
        'delivered_at',
        'skip_reason',
        'delivery_notes',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('delivery_date', $date);
    }
}

// ============================================
// 21. DELIVERY PARTNER MODEL
// ============================================
// File: app/Models/DeliveryPartner.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryPartner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'profile_photo',
        'phone',
        'emergency_contact',
        'vehicle_type',
        'vehicle_make',
        'vehicle_model',
        'vehicle_plate',
        'vehicle_color',
        'license_number',
        'license_expiry',
        'license_photo',
        'id_number',
        'id_photo',
        'verification_status',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'mobile_money_number',
        'current_latitude',
        'current_longitude',
        'location_updated_at',
        'availability_status',
        'is_online',
        'average_rating',
        'total_deliveries',
        'completion_rate',
        'on_time_rate',
        'is_active',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'location_updated_at' => 'datetime',
        'is_online' => 'boolean',
        'average_rating' => 'decimal:2',
        'completion_rate' => 'decimal:2',
        'on_time_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_online', true)
                    ->where('availability_status', 'available');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeNearby($query, $latitude, $longitude, $radiusKm = 5)
    {
        return $query->selectRaw(
            '*, ( 6371 * acos( cos( radians(?) ) *
            cos( radians( current_latitude ) ) *
            cos( radians( current_longitude ) - radians(?) ) +
            sin( radians(?) ) *
            sin( radians( current_latitude ) ) ) ) AS distance',
            [$latitude, $longitude, $latitude]
        )
        ->havingRaw('distance < ?', [$radiusKm])
        ->orderBy('distance');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Helper methods
    public function updateLocation($latitude, $longitude)
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'location_updated_at' => now(),
        ]);
    }

    public function goOnline()
    {
        $this->update([
            'is_online' => true,
            'availability_status' => 'available',
        ]);
    }

    public function goOffline()
    {
        $this->update([
            'is_online' => false,
            'availability_status' => 'offline',
        ]);
    }

    public function setBusy()
    {
        $this->update(['availability_status' => 'busy']);
    }

    public function setAvailable()
    {
        if ($this->is_online) {
            $this->update(['availability_status' => 'available']);
        }
    }

    public function updateRating()
    {
        $stats = $this->reviews()
            ->selectRaw('AVG(delivery_rating) as avg_rating')
            ->first();

        $this->update([
            'average_rating' => $stats->avg_rating ?? 0,
        ]);
    }
}

// ============================================
// 22. DELIVERY MODEL
// ============================================
// File: app/Models/Delivery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'delivery_partner_id',
        'pickup_location_id',
        'pickup_latitude',
        'pickup_longitude',
        'pickup_time',
        'pickup_otp',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_time',
        'delivery_otp',
        'delivery_photo',
        'delivery_notes',
        'distance_km',
        'estimated_time_minutes',
        'actual_time_minutes',
        'status',
        'delivery_fee',
        'partner_earnings',
        'platform_commission',
        'failure_reason',
    ];

    protected $casts = [
        'pickup_latitude' => 'decimal:8',
        'pickup_longitude' => 'decimal:8',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'pickup_time' => 'datetime',
        'delivery_time' => 'datetime',
        'distance_km' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'partner_earnings' => 'decimal:2',
        'platform_commission' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryPartner()
    {
        return $this->belongsTo(DeliveryPartner::class);
    }

    public function pickupLocation()
    {
        return $this->belongsTo(SupplierLocation::class, 'pickup_location_id');
    }

    // Scopes
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['accepted', 'arrived_at_pickup', 'picked_up', 'on_the_way', 'arrived_at_delivery']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    // Helper methods
    public function generatePickupOTP()
    {
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update(['pickup_otp' => $otp]);
        return $otp;
    }

    public function generateDeliveryOTP()
    {
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update(['delivery_otp' => $otp]);
        return $otp;
    }

    public function verifyPickupOTP($otp)
    {
        return $this->pickup_otp === $otp;
    }

    public function verifyDeliveryOTP($otp)
    {
        return $this->delivery_otp === $otp;
    }

    public function calculateActualTime()
    {
        if ($this->pickup_time && $this->delivery_time) {
            $minutes = $this->pickup_time->diffInMinutes($this->delivery_time);
            $this->update(['actual_time_minutes' => $minutes]);
            return $minutes;
        }
        return null;
    }
}

// ============================================
// 23. REVIEW MODEL
// ============================================
// File: app/Models/Review.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'supplier_id',
        'delivery_partner_id',
        'food_rating',
        'service_rating',
        'delivery_rating',
        'overall_rating',
        'review_text',
        'tags',
        'images',
        'supplier_response',
        'response_at',
        'is_approved',
        'is_featured',
        'helpful_count',
        'not_helpful_count',
    ];

    protected $casts = [
        'overall_rating' => 'decimal:2',
        'tags' => 'array',
        'images' => 'array',
        'response_at' => 'datetime',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function deliveryPartner()
    {
        return $this->belongsTo(DeliveryPartner::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeHighRated($query, $minRating = 4.0)
    {
        return $query->where('overall_rating', '>=', $minRating);
    }

    public function scopeWithResponse($query)
    {
        return $query->whereNotNull('supplier_response');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Helper methods
    public function markHelpful()
    {
        $this->increment('helpful_count');
    }

    public function markNotHelpful()
    {
        $this->increment('not_helpful_count');
    }

    public function addSupplierResponse($response)
    {
        $this->update([
            'supplier_response' => $response,
            'response_at' => now(),
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        // Update supplier and delivery partner ratings after review is created
        static::created(function ($review) {
            if ($review->supplier_id) {
                $review->supplier->updateRating();
            }
            if ($review->delivery_partner_id) {
                $review->deliveryPartner->updateRating();
            }
        });
    }
}

// ============================================
// 24. COUPON MODEL
// ============================================
// File: app/Models/Coupon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_order_amount',
        'applicable_to',
        'supplier_ids',
        'category_ids',
        'usage_limit',
        'usage_limit_per_user',
        'times_used',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'supplier_ids' => 'array',
        'category_ids' => 'array',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function usage()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('valid_from', '<=', now())
                    ->where('valid_until', '>=', now());
    }

    public function scopeValid($query)
    {
        return $query->where('valid_until', '>=', now());
    }

    // Helper methods
    public function isValid()
    {
        return $this->is_active &&
               $this->valid_from <= now() &&
               $this->valid_until >= now() &&
               ($this->usage_limit === null || $this->times_used < $this->usage_limit);
    }

    public function canBeUsedByUser($userId)
    {
        if (!$this->isValid()) {
            return false;
        }

        $userUsageCount = $this->usage()
            ->where('user_id', $userId)
            ->count();

        return $userUsageCount < $this->usage_limit_per_user;
    }

    public function calculateDiscount($orderAmount)
    {
        if ($orderAmount < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;

        if ($this->discount_type === 'percentage') {
            $discount = ($orderAmount * $this->discount_value) / 100;
            
            if ($this->max_discount_amount) {
                $discount = min($discount, $this->max_discount_amount);
            }
        } elseif ($this->discount_type === 'fixed') {
            $discount = $this->discount_value;
        }

        return min($discount, $orderAmount);
    }

    public function incrementUsage()
    {
        $this->increment('times_used');
    }
}

// ============================================
// 25. COUPON USAGE MODEL
// ============================================
// File: app/Models/CouponUsage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    protected $table = 'coupon_usage';

    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'discount_amount',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];

    // Relationships
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

// ============================================
// 26. PAYMENT MODEL
// ============================================
// File: app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_reference',
        'order_id',
        'user_id',
        'payment_method',
        'provider',
        'provider_transaction_id',
        'amount',
        'currency',
        'status',
        'failure_reason',
        'paid_at',
        'refunded_at',
        'refund_amount',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Helper methods
    public static function generateReference()
    {
        return 'PAY-' . strtoupper(uniqid());
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update order payment status
        $this->order->update(['payment_status' => 'paid']);
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);
    }

    public function refund($amount = null)
    {
        $refundAmount = $amount ?? $this->amount;

        $this->update([
            'status' => 'refunded',
            'refund_amount' => $refundAmount,
            'refunded_at' => now(),
        ]);

        // Update order payment status
        $this->order->update(['payment_status' => 'refunded']);
    }
}

// ============================================
// 27. SUPPLIER PAYOUT MODEL
// ============================================
// File: app/Models/SupplierPayout.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_reference',
        'supplier_id',
        'period_start',
        'period_end',
        'total_orders',
        'gross_amount',
        'commission_amount',
        'delivery_fees',
        'refunds',
        'adjustments',
        'net_amount',
        'currency',
        'payment_method',
        'payment_reference',
        'status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'gross_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'delivery_fees' => 'decimal:2',
        'refunds' => 'decimal:2',
        'adjustments' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Helper methods
    public static function generateReference()
    {
        return 'PAYOUT-' . strtoupper(uniqid());
    }

    public function markAsPaid($paymentReference = null)
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
            'payment_reference' => $paymentReference,
        ]);
    }
}

// ============================================
// 28. NOTIFICATION MODEL
// ============================================
// File: app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'channel',
        'is_read',
        'read_at',
        'sent_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Helper methods
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }
}

// ============================================
// 29. BANNER MODEL
// ============================================
// File: app/Models/Banner.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'mobile_image_url',
        'banner_type',
        'cta_text',
        'cta_url',
        'display_order',
        'start_date',
        'end_date',
        'is_active',
        'click_count',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    // Helper methods
    public function incrementClicks()
    {
        $this->increment('click_count');
    }

    public function isActive()
    {
        return $this->is_active &&
               $this->start_date <= now() &&
               $this->end_date >= now();
    }
}

// ============================================
// 30. FAVORITE MODEL (Polymorphic)
// ============================================
// File: app/Models/Favorite.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favorable_type',
        'favorable_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeSuppliers($query)
    {
        return $query->where('favorable_type', Supplier::class);
    }

    public function scopeMenuItems($query)
    {
        return $query->where('favorable_type', MenuItem::class);
    }
}

// ============================================
// 31. APP SETTING MODEL
// ============================================
// File: app/Models/AppSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'data_type',
        'group',
        'description',
    ];

    // Helper methods
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->data_type);
    }

    public static function setValue($key, $value, $dataType = 'string')
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'data_type' => $dataType,
            ]
        );
    }

    protected static function castValue($value, $dataType)
    {
        switch ($dataType) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
            case 'decimal':
                return (float) $value;
            case 'json':
            case 'array':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
}