<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\PermissionResolver;

class BusinessType extends Model
{
    protected $table = 'business_types';
        use SoftDeletes;

        protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'features',
        'display_order',
        'is_active',
        'created_by',
        'updated_by',
        'status',
    ];
    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];
    
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_business_types')
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
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
