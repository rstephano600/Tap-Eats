<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Check if super admin already exists
        $existingAdmin = User::where('email', 'superadmin@tapeats.com')->first();
        
        if ($existingAdmin) {
            $existingAdmin->assignRole('super_admin');
            $this->command->info('Super admin role assigned to existing user.');
            return;
        }

        $superAdmin = User::create([
            'name' => 'Robert Stephano, Nuru',
            'username' => 'RobertStephano@tapeats',
            'email' => 'robert.stephano@tapeats.co.tz',
            'phone' => '+255657856790',
            'password' => Hash::make('Robert@EJOS2025'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $superAdmin->assignRole('super_admin');
        
        $this->command->info('Super admin created successfully!');
        $this->command->info('Email: robert.stephano@tapeats.co.tz');
        $this->command->info('Password: Robert@EJOS2025');
    }
}