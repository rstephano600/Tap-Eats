<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class MenuDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create 20 categories
        MenuCategory::factory()
            ->count(20)
            ->create();

        // Create 120 menu items
        MenuItem::factory()
            ->count(120)
            ->create();

        $this->command->info('Menu categories and items seeded successfully.');
    }
}
