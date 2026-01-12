<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\PermissionResolver;


class User extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'user_type_id',
        'user_type',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'verification_code',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    // User Type
    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    // Roles

    // Check role
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users')
            ->withPivot('status', 'created_by', 'updated_by')
            ->wherePivot('status', 'active');
    }

    public function directPermissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withPivot('type', 'status')
            ->wherePivot('status', 'active');
    }

    public function hasPermission(string $permissionName): bool
    {
        $permission = Permission::where('name', $permissionName)
            ->where('status', 'active')
            ->first();

        if (!$permission) return false;

        // 1️⃣ user revoke
        if ($this->directPermissions()
            ->where('permissions.id', $permission->id)
            ->where('user_permissions.type', 'revoke')
            ->exists()) {
            return false;
        }

        // 2️⃣ user grant
        if ($this->directPermissions()
            ->where('permissions.id', $permission->id)
            ->where('user_permissions.type', 'grant')
            ->exists()) {
            return true;
        }

        // 3️⃣ role permissions
        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permission) {
                $q->where('permissions.id', $permission->id)
                  ->where('permission_roles.status', 'active');
            })
            ->exists();
    }
    public function userPermissions()
{
    return $this->hasMany(UserPermission::class)
        ->where('status', 'active');
}


    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    public function deliveryPartner()
    {
        return $this->hasOne(DeliveryPartner::class);
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function cart()
    {
        return $this->hasMany(UserCart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'customer_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopeCustomers($query)
    {
        return $query->where('user_type', 'customer');
    }

    public function scopeSuppliers($query)
    {
        return $query->where('user_type', 'supplier');
    }

    public function scopeDeliveryPartners($query)
    {
        return $query->where('user_type', 'delivery_partner');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at')
                    ->whereNotNull('phone_verified_at');
    }

    // Helper methods
    public function isCustomer()
    {
        return $this->user_type === 'customer';
    }

    public function isSupplier()
    {
        return $this->user_type === 'supplier';
    }

    public function isDeliveryPartner()
    {
        return $this->user_type === 'delivery_partner';
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function isVerified()
    {
        return !is_null($this->email_verified_at) && !is_null($this->phone_verified_at);
    }



    
}
