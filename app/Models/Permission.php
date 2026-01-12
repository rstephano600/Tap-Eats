<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'descriptions', 'status'];


public function roles()
{
    return $this->belongsToMany(Role::class, 'permission_roles')
        ->wherePivot('status', 'active');
}

}
