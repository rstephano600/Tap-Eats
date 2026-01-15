<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\Supplier;
use App\Models\MenuCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        $name = ucfirst($this->faker->unique()->words(3, true));

        return [
            'supplier_id'        => Supplier::inRandomOrder()->value('id'),
            'menu_category_id'   => MenuCategory::inRandomOrder()->value('id'),

            'name'               => $name,
            'slug'               => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'description'        => $this->faker->paragraph,

            'price'              => $this->faker->numberBetween(3000, 30000),
            'discounted_price'   => $this->faker->boolean(30)
                                        ? $this->faker->numberBetween(2000, 25000)
                                        : null,

            'image_url'          => null,
            'gallery_images'     => null,

            'preparation_time'   => $this->faker->numberBetween(5, 40),
            'serves'             => $this->faker->numberBetween(1, 4),
            'portion_size'       => $this->faker->randomElement(['Small', 'Regular', 'Large']),
            'calories'           => $this->faker->numberBetween(200, 1200),

            'is_vegetarian'      => $this->faker->boolean(20),
            'is_vegan'           => $this->faker->boolean(10),
            'is_gluten_free'     => $this->faker->boolean(15),
            'is_halal'           => $this->faker->boolean(60),
            'is_spicy'           => $this->faker->boolean(30),

            'allergens'          => $this->faker->randomElements(
                                        ['nuts', 'dairy', 'eggs', 'soy', 'gluten'],
                                        $this->faker->numberBetween(0, 3)
                                    ),

            'ingredients'        => $this->faker->words(
                                        $this->faker->numberBetween(3, 8)
                                    ),

            'is_available'       => $this->faker->boolean(90),
            'available_times'    => ['09:00-14:00', '17:00-22:00'],
            'stock_quantity'     => $this->faker->boolean(30)
                                        ? $this->faker->numberBetween(5, 100)
                                        : null,

            'is_featured'        => $this->faker->boolean(10),
            'is_popular'         => $this->faker->boolean(20),

            'view_count'         => $this->faker->numberBetween(0, 1000),
            'order_count'        => $this->faker->numberBetween(0, 300),
            'average_rating'     => $this->faker->randomFloat(2, 2.5, 5),

            'display_order'      => $this->faker->numberBetween(1, 50),
            'is_active'          => true,
            'status'             => 'active',

            'created_by'         => User::inRandomOrder()->value('id'),
            'updated_by'         => User::inRandomOrder()->value('id'),
        ];
    }
}
