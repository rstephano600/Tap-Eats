<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermissionUser extends Model
{
    protected $table = 'permission_users';
    
    protected $fillable = [
        'permission_id',
        'user_id',
        'status',
        'created_by',
        'updated_by'
    ];
    
    protected $casts = [
        'status' => 'string',
    ];
    
    protected $attributes = [
        'status' => 'active',
    ];
    
    // Relationships
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
    
    public function scopeLocked($query)
    {
        return $query->where('status', 'locked');
    }
    
    public function scopeExcludingDeleted($query)
    {
        return $query->where('status', '!=', 'deleted');
    }
}