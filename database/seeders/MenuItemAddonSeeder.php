<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\MenuItemAddon;

class MenuItemAddonSeeder extends Seeder
{
    public function run(): void
    {
        $addonSets = [
            ['name' => 'Extra Cheese',  'price' => 1500],
            ['name' => 'Bacon',         'price' => 2500],
            ['name' => 'Extra Sauce',   'price' => 1000],
            ['name' => 'Mushrooms',     'price' => 1200],
            ['name' => 'Avocado',       'price' => 2000],
            ['name' => 'Chili Sauce',   'price' => 800],
        ];

        MenuItem::all()->each(function ($item) use ($addonSets) {

            // Each item gets 3–5 add-ons
            collect($addonSets)
                ->shuffle()
                ->take(rand(3, 5))
                ->values()
                ->each(function ($addon, $index) use ($item) {

                    MenuItemAddon::factory()->create([
                        'menu_item_id' => $item->id,
                        'addon_name'   => $addon['name'],
                        'price'        => $addon['price'],
                        'display_order'=> $index + 1,
                        'max_quantity' => rand(1, 3),
                    ]);

                });

        });

        $this->command->info('Menu item add-ons seeded successfully.');
    }
}
