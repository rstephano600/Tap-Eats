<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'customer_id',
        'guest_session_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'order_id',
        'order_number',
        'category',
        'priority',
        'subject',
        'message',
        'attachments',
        'status',
        'assigned_to',
        'resolved_at',
        'resolution_notes',
        'rating',
        'feedback',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'attachments' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function guestSession()
    {
        return $this->belongsTo(GuestSession::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(SupportTicketReply::class);
    }
}