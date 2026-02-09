<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'guest_session_id',
        'supplier_id',
        'service_type_id',
        'order_type',
        'order_status',
        'payment_method',
        'payment_status',
        'payment_reference',
        'delivery_address_id',
        'delivery_address_text',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_phone',
        'delivery_contact_name',
        'scheduled_at',
        'accepted_at',
        'prepared_at',
        'dispatched_at',
        'delivered_at',
        'cancelled_at',
        'estimated_delivery_time',
        'subtotal',
        'delivery_fee',
        'service_fee',
        'tax_amount',
        'discount_amount',
        'coupon_code',
        'total_amount',
        'special_instructions',
        'cancellation_reason',
        'rejection_reason',
        'delivery_distance',
        'delivery_time',
        'delivery_costs',
        'delivery_otp',
        'delivery_photo',
        'delivery_notes',
        'created_by',
        'updated_by',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'accepted_at' => 'datetime',
        'prepared_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'delivery_time' => 'datetime',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
    ];

    protected $appends = [
        'is_cancellable',
        'is_editable',
        'status_badge_color',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function guestSession()
    {
        return $this->belongsTo(GuestSession::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(CustomerAddress::class, 'delivery_address_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('order_status', 'accepted');
    }

    public function scopePreparing($query)
    {
        return $query->where('order_status', 'preparing');
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('order_status', 'cancelled');
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('payment_status', ['pending', 'failed']);
    }

    public function scopeScheduled($query)
    {
        return $query->where('order_type', 'scheduled')
            ->whereNotNull('scheduled_at');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Accessors
    public function getIsCancellableAttribute()
    {
        return in_array($this->order_status, ['pending', 'accepted']);
    }

    public function getIsEditableAttribute()
    {
        return in_array($this->order_status, ['pending', 'accepted']);
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->order_status) {
            'pending' => 'warning',
            'accepted' => 'info',
            'preparing' => 'primary',
            'ready' => 'success',
            'dispatched' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'rejected' => 'danger',
            'failed' => 'danger',
            default => 'secondary',
        };
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2);
    }

    // Methods
    public function canBeAccepted()
    {
        return $this->order_status === 'pending';
    }

    public function canBeCancelled()
    {
        return in_array($this->order_status, ['pending', 'accepted']);
    }

    public function canBeRejected()
    {
        return $this->order_status === 'pending';
    }

    public function calculateDeliveryTime()
    {
        if ($this->dispatched_at && $this->delivered_at) {
            return $this->dispatched_at->diffInMinutes($this->delivered_at);
        }
        return null;
    }

    public function getTotalItems()
    {
        return $this->orderItems->sum('quantity');
    }


    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('order_status', ['confirmed', 'preparing', 'ready', 'dispatched']);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('order_status', ['delivered', 'completed']);
    }

    /**
     * Accessors & Mutators
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->order_status));
    }

    public function getIsDeliveryAttribute()
    {
        return $this->serviceType && stripos($this->serviceType->name, 'delivery') !== false;
    }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->order_status, ['pending', 'confirmed']);
    }
}