<?php

namespace Database\Factories;

use App\Models\MenuItemVariant;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemVariantFactory extends Factory
{
    protected $model = MenuItemVariant::class;

    public function definition(): array
    {
        return [
            'menu_item_id'     => MenuItem::inRandomOrder()->value('id'),

            'variant_name'     => $this->faker->randomElement([
                'Small',
                'Medium',
                'Large',
                'Extra Large',
            ]),

            // Price difference from base price
            'price_adjustment' => $this->faker->randomElement([
                -2000, -1000, 0, 1000, 2000, 3000
            ]),

            'is_available'     => $this->faker->boolean(90),
            'display_order'    => $this->faker->numberBetween(1, 4),
            'is_active'        => true,
            'status'           => 'active',

            'created_by'       => User::inRandomOrder()->value('id'),
            'updated_by'       => User::inRandomOrder()->value('id'),
        ];
    }
}

