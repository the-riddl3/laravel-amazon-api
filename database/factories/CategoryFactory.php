<?php

namespace Database\Factories;

use App\Models\Category;
use Egulias\EmailValidator\Result\Reason\CRLFAtTheEnd;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Category::NAME => fake()->name,
        ];
    }
}
