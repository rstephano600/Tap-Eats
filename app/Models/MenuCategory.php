<?php

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
    'image',
    'display_order',
    'is_active',
    'created_by',
    'updated_by',
    'status',
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