<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Supplier', 'slug' => 'supplier'],
            ['name' => 'Staff', 'slug' => 'staff'],
            ['name' => 'Customer', 'slug' => 'customer'],
            ['name' => 'Delivery', 'slug' => 'delivery-food'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'descriptions' => $role['name'].' role'
                ]
            );
        }
    }
}

