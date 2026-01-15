<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\MenuItemVariant;

class MenuItemVariantSeeder extends Seeder
{
    public function run(): void
    {
        $variants = [
            ['name' => 'Small',        'adjustment' => -2000],
            ['name' => 'Medium',       'adjustment' => 0],
            ['name' => 'Large',        'adjustment' => 2000],
            ['name' => 'Extra Large',  'adjustment' => 4000],
        ];

        MenuItem::all()->each(function ($item) use ($variants) {

            foreach ($variants as $order => $variant) {
                MenuItemVariant::factory()->create([
                    'menu_item_id'     => $item->id,
                    'variant_name'     => $variant['name'],
                    'price_adjustment' => $variant['adjustment'],
                    'display_order'    => $order + 1,
                ]);
            }

        });

        $this->command->info('Menu item variants seeded successfully.');
    }
}
