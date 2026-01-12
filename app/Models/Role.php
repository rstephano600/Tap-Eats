<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'descriptions', 'status'];


        public function users()
    {
        return $this->belongsToMany(User::class, 'role_users')
            ->withPivot('status');
    }


    public function permissions()
{
    return $this->belongsToMany(Permission::class, 'permission_roles')
        ->wherePivot('status', 'active');
}


}
