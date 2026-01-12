<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table = 'permission_roles';

    protected $fillable = [
        'permission_id',
        'role_id',
        'status',
        'created_by',
        'updated_by',
    ];

    // ✅ THIS RELATIONSHIP MUST EXIST
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // ✅ THIS RELATIONSHIP MUST EXIST
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
