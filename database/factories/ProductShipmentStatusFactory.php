<?php

namespace Database\Factories;

use App\Enums\ShipmentState;
use App\Models\ProductShipment;
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
            ProductShipmentStatus::SHIPMENT_ID => ProductShipment::factory()->create(),
            ProductShipmentStatus::STATE => ShipmentState::Unpaid,
            ProductShipmentStatus::TIME => now(),
        ];
    }
}
