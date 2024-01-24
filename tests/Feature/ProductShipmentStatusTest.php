<?php

namespace Tests\Feature;

use App\Enums\ShipmentState;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductShipmentStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductShipmentStatusTest extends TestCase
{
    use RefreshDatabase;

    public function testStoringPurchaseStartsShipmentProcess()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->post('/api/product-purchases', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertStatus(201);

        $this->assertDatabaseCount('product_purchases', 1);
        $this->assertDatabaseHas('product_shipment_statuses', [
            'state' => ShipmentState::Unpaid->value
        ]);
    }

    public function testStoreShipmentStatus()
    {
        $purchase = ProductPurchase::factory()->create();

        Sanctum::actingAs(User::factory()->admin()->create());

        $this->post("/api/product-purchases/$purchase->id/shipment-statuses", [
            'state' => ShipmentState::Delivered->value,
            'time' => now()->toString(),
        ])->assertStatus(201);

        $this->assertDatabaseCount('product_shipment_statuses', 1);
    }

    public function testStoreShipmentUnauthenticated()
    {
        $purchase = ProductPurchase::factory()->create();

        $this->post("/api/product-purchases/$purchase->id/shipment-statuses", [
            'state' => ShipmentState::Delivered->value,
            'time' => now()->toString(),
        ])->assertStatus(302);

        $this->assertDatabaseCount('product_shipment_statuses', 0);
    }

    public function testStoreShipmentUnauthorized()
    {
        $purchase = ProductPurchase::factory()->create();

        Sanctum::actingAs($purchase->buyer);

        $this->post("/api/product-purchases/$purchase->id/shipment-statuses", [
            'state' => ShipmentState::Delivered->value,
            'time' => now()->toString(),
        ])->assertStatus(403);

        $this->assertDatabaseCount('product_shipment_statuses', 0);
    }

    public function testIndexShipmentStatus()
    {
        /** @var ProductShipmentStatus $shipmentStatus */
        $shipmentStatus = ProductShipmentStatus::factory()->create();
        $purchase = $shipmentStatus->purchase;

        Sanctum::actingAs($purchase->buyer);

        $this->get("/api/product-purchases/$purchase->id/shipment-statuses")
            ->assertStatus(200)
            ->assertJsonFragment([
                'state' => $shipmentStatus->state,
            ]);
    }

    public function testIndexShipmentStatusUnauthenticated()
    {
        /** @var ProductShipmentStatus $shipmentStatus */
        $shipmentStatus = ProductShipmentStatus::factory()->create();
        $purchase = $shipmentStatus->purchase;

        $this->get("/api/product-purchases/$purchase->id/shipment-statuses")
            ->assertStatus(302);
    }

    public function testIndexShipmentStatusUnauthorized()
    {
        /** @var ProductShipmentStatus $shipmentStatus */
        $shipmentStatus = ProductShipmentStatus::factory()->create();
        $purchase = $shipmentStatus->purchase;

        Sanctum::actingAs(User::factory()->create());

        $this->get("/api/product-purchases/$purchase->id/shipment-statuses")
            ->assertStatus(403);
    }

    public function testIndexShipmentStatusAsAdmin()
    {
        /** @var ProductShipmentStatus $shipmentStatus */
        $shipmentStatus = ProductShipmentStatus::factory()->create();
        $purchase = $shipmentStatus->purchase;

        Sanctum::actingAs(User::factory()->admin()->create());

        $this->get("/api/product-purchases/$purchase->id/shipment-statuses")
            ->assertStatus(200);
    }
}
