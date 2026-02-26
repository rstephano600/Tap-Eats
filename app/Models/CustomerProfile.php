<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'created_by',
        'updated_by',
        'status',
    ];

    protected $casts = [
        'dietary_preferences' => 'array',
        'allergies' => 'array',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'date_of_birth' => 'date',
    ];

    /* Relationships */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
