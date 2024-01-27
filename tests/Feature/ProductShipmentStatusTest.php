<?php

namespace Tests\Feature;

use App\Enums\ShipmentState;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductShipment;
use App\Models\ProductShipmentStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductShipmentStatusTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreShipmentStatus()
    {
        $shipment = ProductShipment::factory()->create();
        $purchase = $shipment->purchase;

        Sanctum::actingAs(User::factory()->admin()->create());

        $this->post("/api/product-purchases/$purchase->id/shipments/$shipment->id/statuses", [
            'state' => ShipmentState::Delivered->value,
            'time' => now()->toString(),
        ])->assertStatus(201);

        $this->assertDatabaseCount('product_shipment_statuses', 1);
    }

    public function testStoreShipmentUnauthenticated()
    {
        $shipment = ProductShipment::factory()->create();
        $purchase = $shipment->purchase;

        $this->post("/api/product-purchases/$purchase->id/shipments/$shipment->id/statuses", [
            'state' => ShipmentState::Delivered->value,
            'time' => now()->toString(),
        ])->assertStatus(302);

        $this->assertDatabaseCount('product_shipment_statuses', 0);
    }

    public function testStoreShipmentUnauthorized()
    {
        $shipment = ProductShipment::factory()->create();
        $purchase = $shipment->purchase;

        Sanctum::actingAs($purchase->buyer);

        $this->post("/api/product-purchases/$purchase->id/shipments/$shipment->id/statuses", [
            'state' => ShipmentState::Delivered->value,
            'time' => now()->toString(),
        ])->assertStatus(403);

        $this->assertDatabaseCount('product_shipment_statuses', 0);
    }

    public function testIndexShipmentStatus()
    {
        /** @var ProductShipmentStatus $shipmentStatus */
        $shipmentStatus = ProductShipmentStatus::factory()->create();
        $shipment = $shipmentStatus->shipment;
        $purchase = $shipment->purchase;

        Sanctum::actingAs($purchase->buyer);

        $this->get("/api/product-purchases/$purchase->id/shipments/$shipment->id/statuses")
            ->assertStatus(200)
            ->assertJsonFragment([
                'state' => $shipmentStatus->state,
            ]);
    }

    public function testIndexShipmentStatusUnauthenticated()
    {
        /** @var ProductShipmentStatus $shipmentStatus */
        $shipmentStatus = ProductShipmentStatus::factory()->create();
        $shipment = $shipmentStatus->shipment;
        $purchase = $shipment->purchase;

        $this->get("/api/product-purchases/$purchase->id/shipments/$shipment->id/statuses")
            ->assertStatus(302);
    }

    public function testIndexShipmentStatusUnauthorized()
    {
        /** @var ProductShipmentStatus $shipmentStatus */
        $shipmentStatus = ProductShipmentStatus::factory()->create();
        $shipment = $shipmentStatus->shipment;
        $purchase = $shipment->purchase;

        Sanctum::actingAs(User::factory()->create());

        $this->get("/api/product-purchases/$purchase->id/shipments/$shipment->id/statuses")
            ->assertStatus(403);
    }

    public function testIndexShipmentStatusAsAdmin()
    {
        /** @var ProductShipmentStatus $shipmentStatus */
        $shipmentStatus = ProductShipmentStatus::factory()->create();
        $shipment = $shipmentStatus->shipment;
        $purchase = $shipment->purchase;

        Sanctum::actingAs(User::factory()->admin()->create());

        $this->get("/api/product-purchases/$purchase->id/shipments/$shipment->id/statuses")
            ->assertStatus(200);
    }
}
