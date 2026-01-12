<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestSession extends Model
{
    use SoftDeletes;

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
        'created_by',
        'updated_by',
        'status',
    ];

    protected $casts = [
        'preferences' => 'array',
        'last_activity_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
