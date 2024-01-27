<?php

namespace Database\Factories;

use App\Models\ProductPurchase;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductShipment>
 */


class ProductShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_id' => ProductPurchase::factory()->create()->id,
            'user_address_id' => UserAddress::factory()->create()->id,
        ];
    }
}
