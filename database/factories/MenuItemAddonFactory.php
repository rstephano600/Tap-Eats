<?php

namespace Database\Factories;

use App\Models\MenuItemAddon;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemAddonFactory extends Factory
{
    protected $model = MenuItemAddon::class;

    public function definition(): array
    {
        return [
            'menu_item_id' => MenuItem::inRandomOrder()->value('id'),

            'addon_name' => $this->faker->randomElement([
                'Extra Cheese',
                'Bacon',
                'Extra Sauce',
                'Avocado',
                'Mushrooms',
                'Fried Egg',
                'Chili Sauce',
                'Garlic Butter',
            ]),

            'price' => $this->faker->randomElement([
                500, 1000, 1500, 2000, 2500, 3000
            ]),

            'is_available' => $this->faker->boolean(90),
            'max_quantity' => $this->faker->numberBetween(1, 3),
            'display_order' => $this->faker->numberBetween(1, 10),
            'is_active' => true,
            'status' => 'active',

            'created_by' => User::inRandomOrder()->value('id'),
            'updated_by' => User::inRandomOrder()->value('id'),
        ];
    }
}
