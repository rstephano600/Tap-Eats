<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'supplier_id',
        'profile_image',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
        'last_login_ip',
        'username',
        'user_type_id',
        'user_type',
        'status',
        'verification_code',
        'last_login_at',
        'login_times',
        'login_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
        'login_times'   => 'integer',
    ];

    public function supplier(): \Illuminate\Database\Eloquent\Relations\HasOne
{
    return $this->hasOne(Supplier::class);
}

public function supplierMemberships(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(SupplierUser::class);
}

// Get the supplier this user belongs to (owner OR member)
public function getAssociatedSupplier(): ?Supplier
{
    // Direct owner
    if ($this->supplier) {
        return $this->supplier;
    }

    // Member via supplier_user
    $membership = $this->supplierMemberships()->first();
    return $membership ? Supplier::find($membership->supplier_id) : null;
}


// App/Models/Supplier.php
public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(SupplierUser::class);
}

public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(User::class, 'user_id');
}
 
    public function assignedOrders()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    public function customerOrders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    // Helper Methods
    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isRestaurantManager()
    {
        return $this->hasRole('restaurant_manager');
    }

    public function isDriver()
    {
        return $this->hasRole('driver');
    }

    public function isCashier()
    {
        return $this->hasRole('cashier');
    }

    /**
     * Check if user can manage a specific supplier
     */

    /**
     * Get accessible suppliers for this user
     */

    /**
     * Scope to filter users by supplier
     */
    public function scopeForSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope to get active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    // User Type
    public function userType()
    {
        return $this->belongsTo(UserType::class);
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


    public function isVerified()
    {
        return !is_null($this->email_verified_at) && !is_null($this->phone_verified_at);
    }
public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_user')
            ->withPivot(['role_id', 'is_primary', 'is_active', 'joined_at', 'invited_by'])
            ->withTimestamps();
    }

    /**
     * Get active suppliers for this user
     */
    public function activeSuppliers()
    {
        return $this->suppliers()->wherePivot('is_active', true);
    }

    /**
     * Get primary supplier for this user
     */
    public function primarySupplier()
    {
        return $this->suppliers()->wherePivot('is_primary', true)->first();
    }

    /**
     * Get user's role for a specific supplier
     */
    public function getRoleForSupplier($supplierId)
    {
        $pivot = $this->suppliers()->where('supplier_id', $supplierId)->first()?->pivot;
        
        if ($pivot) {
            return \Spatie\Permission\Models\Role::find($pivot->role_id);
        }
        
        return null;
    }

    /**
     * Check if user has access to supplier
     */
    public function hasAccessToSupplier($supplierId)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        return $this->activeSuppliers()->where('supplier_id', $supplierId)->exists();
    }

    /**
     * Check if user can manage supplier (admin or manager role)
     */
    public function canManageSupplier($supplierId)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        $role = $this->getRoleForSupplier($supplierId);
        
        return $role && in_array($role->name, ['admin', 'restaurant_manager']);
    }

    /**
     * Get all accessible suppliers
     */
    public function getAccessibleSuppliers()
    {
        if ($this->isSuperAdmin()) {
            return Supplier::where('Status', 'Active')->get();
        }
        
        return $this->activeSuppliers()->where('Status', 'Active')->get();
    }

    /**
     * Attach supplier with role
     */
    public function attachSupplierWithRole($supplierId, $roleId, $isPrimary = false, $invitedBy = null)
    {
        return $this->suppliers()->attach($supplierId, [
            'role_id' => $roleId,
            'is_primary' => $isPrimary,
            'is_active' => true,
            'joined_at' => now(),
            'invited_by' => $invitedBy,
        ]);
    }

    /**
     * Update user's role for a supplier
     */
    public function updateSupplierRole($supplierId, $roleId)
    {
        return $this->suppliers()->updateExistingPivot($supplierId, [
            'role_id' => $roleId,
        ]);
    }

    /**
     * Remove user from supplier
     */
    public function detachSupplier($supplierId)
    {
        return $this->suppliers()->detach($supplierId);
    }

}