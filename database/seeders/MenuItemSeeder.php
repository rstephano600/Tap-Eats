<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\Supplier;
use App\Models\MenuCategory;
use App\Models\User;
use Illuminate\Support\Str;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $supplier  = Supplier::first();
        $category  = MenuCategory::first();
        $user      = User::first();

        if (!$supplier || !$category || !$user) {
            $this->command->warn('Missing supplier, category, or user. Seeder skipped.');
            return;
        }

        $items = [
            [
                'name' => 'Grilled Chicken Burger',
                'price' => 12000,
                'discounted_price' => 10000,
                'description' => 'Juicy grilled chicken burger served with fresh lettuce and sauce.',
                'is_halal' => true,
                'is_popular' => true,
                'calories' => 650,
            ],
            [
                'name' => 'Vegetarian Pizza',
                'price' => 18000,
                'description' => 'Stone baked pizza topped with fresh vegetables and mozzarella.',
                'is_vegetarian' => true,
                'is_featured' => true,
                'calories' => 720,
            ],
            [
                'name' => 'Spicy Beef Wrap',
                'price' => 14000,
                'description' => 'Spicy beef wrap with homemade chili sauce.',
                'is_spicy' => true,
                'serves' => 1,
                'calories' => 580,
            ],
        ];

        foreach ($items as $index => $item) {

            $slug = Str::slug($item['name']);

            if (MenuItem::where('slug', $slug)->exists()) {
                $slug .= '-' . uniqid();
            }

            MenuItem::create([
                'supplier_id'        => $supplier->id,
                'menu_category_id'   => $category->id,

                'name'               => $item['name'],
                'slug'               => $slug,
                'description'        => $item['description'] ?? null,

                'price'              => $item['price'],
                'discounted_price'   => $item['discounted_price'] ?? null,

                'image_url'          => null,
                'gallery_images'     => null,

                'preparation_time'   => rand(10, 25),
                'serves'             => $item['serves'] ?? 1,
                'portion_size'       => 'Regular',
                'calories'           => $item['calories'] ?? null,

                'is_vegetarian'      => $item['is_vegetarian'] ?? false,
                'is_vegan'           => false,
                'is_gluten_free'     => false,
                'is_halal'           => $item['is_halal'] ?? false,
                'is_spicy'           => $item['is_spicy'] ?? false,

                'allergens'          => ['nuts', 'dairy'],
                'ingredients'        => ['salt', 'oil', 'pepper'],

                'is_available'       => true,
                'available_times'    => ['09:00-14:00', '17:00-22:00'],
                'stock_quantity'     => rand(10, 50),
                'is_featured'        => $item['is_featured'] ?? false,
                'is_popular'         => $item['is_popular'] ?? false,

                'view_count'         => rand(50, 200),
                'order_count'        => rand(10, 80),
                'average_rating'     => rand(35, 50) / 10,

                'display_order'      => $index + 1,
                'is_active'          => true,
                'status'             => 'active',

                'created_by'         => $user->id,
                'updated_by'         => $user->id,
            ]);
        }

        $this->command->info('Menu items seeded successfully.');
    }
}
