<?php

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
