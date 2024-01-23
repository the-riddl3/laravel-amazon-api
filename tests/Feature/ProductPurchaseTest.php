<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductPurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePurchase()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->post('/api/product-purchases', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertStatus(201);

        $this->assertDatabaseCount('product_purchases', 1);
    }

    public function testCreatePurchaseUnauthenticated()
    {
        $product = Product::factory()->create();

        $this->post('/api/product-purchases', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertStatus(302);

        $this->assertDatabaseCount('product_purchases', 0);
    }

    public function testShowPurchase()
    {
        $purchase = ProductPurchase::factory()->create();

        Sanctum::actingAs($purchase->buyer);

        $this->get("/api/product-purchases/$purchase->id")
            ->assertStatus(200);
    }

    public function testShowPurchaseUnauthorized()
    {
        $purchase = ProductPurchase::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->get("/api/product-purchases/$purchase->id")
            ->assertStatus(403);
    }

    public function testShowPurchaseAsAdmin()
    {
        $purchase = ProductPurchase::factory()->create();

        Sanctum::actingAs(User::factory()->admin()->create());

        $this->get("/api/product-purchases/$purchase->id")
            ->assertStatus(200);
    }
}
