<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $customerRole   = Role::where('slug', 'customer')->first();

        if ($user = User::first()) {
            $user->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }

        User::where('id', '>', 1)->each(function ($user) use ($customerRole) {
            $user->roles()->syncWithoutDetaching([$customerRole->id]);
        });
    }
}
