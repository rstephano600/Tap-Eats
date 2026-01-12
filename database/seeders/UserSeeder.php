<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
public function run()
    {
                // Get default user type (Customer)
        $userType = UserType::where('name', 'Super Admin')->first();

        $users = [
            [
                'name' => 'Robert',
                'username' => 'Robert@ejossolution',
                'email' => 'robert@ejossolution.co.tz',
                'phone' => '0657856790',
                'password' => Hash::make('RobertEJOS2026@'),
                'user_type_id' => $userType?->id,
            ],
            
        ];
        
        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
