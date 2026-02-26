<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'url',
        'ip_address',
        'user_agent',
        'method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
