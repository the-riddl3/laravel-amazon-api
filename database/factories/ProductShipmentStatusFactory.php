<?php

namespace Database\Factories;

use App\Enums\ShipmentState;
use App\Models\ProductPurchase;
use App\Models\ProductShipmentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductShipmentStatus>
 */
class ProductShipmentStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_id' => ProductPurchase::factory()->create(),
            'state' => ShipmentState::Unpaid,
            'time' => now(),
        ];
    }
}
