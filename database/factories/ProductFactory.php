<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Product::NAME => fake()->name,
            Product::DESCRIPTION => fake()->text,
            Product::PRICE => fake()->numberBetween(100,100000),
            Product::USER_ID => User::factory()->createOne()->id,
            Product::CATEGORY_ID => Category::factory()->createOne()->id,
        ];
    }
}
