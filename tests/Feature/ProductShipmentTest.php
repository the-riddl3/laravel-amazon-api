<?php

namespace Tests\Feature;

use App\Models\ProductPurchase;
use App\Models\ProductShipment;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductShipmentTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreShipment() {
        $purchase = ProductPurchase::factory()->create();
        $address = UserAddress::factory()->create([UserAddress::USER_ID => $purchase->buyer_id]);

        Sanctum::actingAs($purchase->buyer);

        $this->post("/api/product-purchases/$purchase->id/shipments", [
            'user_address_id' => $address->id,
        ])->assertStatus(201);

        $this->assertDatabaseCount('product_shipments', 1);
    }

    public function testIndexShipmentUnauthenticated() {
        $shipment = ProductShipment::factory()->create();
        $purchase = $shipment->purchase;

        $this->get("/api/product-purchases/$purchase->id/shipments")
            ->assertStatus(302);
    }

    public function testIndexShipmentUnauthorized() {
        $shipment = ProductShipment::factory()->create();
        $purchase = $shipment->purchase;

        Sanctum::actingAs(User::factory()->create());

        $this->get("/api/product-purchases/$purchase->id/shipments")
            ->assertStatus(403);
    }

    public function testIndexShipmentAsBuyer() {
        $shipment = ProductShipment::factory()->create();
        $purchase = $shipment->purchase;

        Sanctum::actingAs($purchase->buyer);

        $this->get("/api/product-purchases/$purchase->id/shipments")
            ->assertStatus(200);
    }

    public function testIndexShipmentAsAdmin() {
        $shipment = ProductShipment::factory()->create();
        $purchase = $shipment->purchase;

        Sanctum::actingAs(User::factory()->admin()->create());

        $this->get("/api/product-purchases/$purchase->id/shipments")
            ->assertStatus(200);
    }
}
