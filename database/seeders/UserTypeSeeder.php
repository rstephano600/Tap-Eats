<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserType;

class UserTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Super Admin', 'description' => 'System owner'],
            ['name' => 'Admin', 'description' => 'System administrator'],
            ['name' => 'Supplier', 'description' => 'Food supplier/vendor'],
            ['name' => 'Staff', 'description' => 'Internal staff'],
            ['name' => 'Customer', 'description' => 'End user'],
            ['name' => 'Delivery', 'description' => 'Food Deliver'],
        ];

        foreach ($types as $type) {
            UserType::firstOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
