<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'created_by',
        'updated_by',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the supplier that owns the location
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who created this location
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this location
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope to get only active locations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    /**
     * Scope to get only primary location
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to filter by supplier
     */
    public function scopeForSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Get formatted full address
     */
    public function getFullAddressAttribute()
    {
        $address = collect([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ])->filter()->implode(', ');

        return $address;
    }

    /**
     * Get Google Maps link
     */
    public function getGoogleMapsLinkAttribute()
    {
        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }

    /**
     * Get formatted coordinates
     */
    public function getCoordinatesAttribute()
    {
        return "{$this->latitude}, {$this->longitude}";
    }

    /**
     * Check if location is the primary one
     */
    public function isPrimary()
    {
        return $this->is_primary;
    }

    /**
     * Check if location is active
     */
    public function isActive()
    {
        return $this->is_active && $this->status === 'active';
    }

    /**
     * Calculate distance to another location (in kilometers)
     */
    public function distanceTo($latitude, $longitude)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Ensure only one primary location per supplier
        static::creating(function ($location) {
            if ($location->is_primary) {
                static::where('supplier_id', $location->supplier_id)
                    ->update(['is_primary' => false]);
            }
        });

        static::updating(function ($location) {
            if ($location->is_primary && $location->isDirty('is_primary')) {
                static::where('supplier_id', $location->supplier_id)
                    ->where('id', '!=', $location->id)
                    ->update(['is_primary' => false]);
            }
        });
    }
}