<?php

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
        'business_type_id',
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

public function businessType()
{
    return $this->belongsTo(BusinessType::class);
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
    public function foods()
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
