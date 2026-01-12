<?php


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
