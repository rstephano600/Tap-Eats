<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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
        public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }
public function menuCategory(): BelongsTo
{
    return $this->belongsTo(MenuCategory::class, 'menu_category_id');
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
