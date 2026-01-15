<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuCategoryFactory extends Factory
{
    protected $model = MenuCategory::class;

    public function definition(): array
    {
        return [
            'supplier_id'   => Supplier::inRandomOrder()->value('id'),
            'category_name' => ucfirst($this->faker->unique()->words(2, true)),
            'description'   => $this->faker->sentence(10),
            'image'         => null,
            'display_order' => $this->faker->numberBetween(1, 20),
            'is_active'     => true,
            'status'        => 'active',
            'created_by'    => User::inRandomOrder()->value('id'),
            'updated_by'    => User::inRandomOrder()->value('id'),
        ];
    }
}
