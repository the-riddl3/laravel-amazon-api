<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Tests\CreatesApplication;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateProductAuthenticated()
    {
        /** @var User $user */
        $user = User::factory()->createOne();

        Sanctum::actingAs($user);

        $this->post('/api/products', [
            Product::NAME => 'Cheap Laptop',
            Product::DESCRIPTION => 'Works, I think...',
            Product::PRICE => 15000,
            Product::CATEGORY_ID => Category::factory()->createOne()->id,
        ])->assertStatus(200);

        $this->assertDatabaseCount('products', 1);
    }

    public function testCreateProductUnauthenticated() {
        $this->post('/api/products', [
            Product::NAME => 'Cheap Laptop',
            Product::DESCRIPTION => 'Works, I think...',
            Product::PRICE => 15000,
            Product::CATEGORY_ID => Category::factory()->createOne()->id,
        ])->assertStatus(302);
    }

    public function testReadProduct() {
        $product = Product::factory()->createOne();

        $this->get("api/products/$product->id")->assertStatus(200);
    }

    public function testUpdateProductAsOwner()
    {
        $product = Product::factory()->createOne();

        Sanctum::actingAs($product->seller);

        $this->put("api/products/$product->id", [
            Product::NAME => $product->name . ' appendix',
            Product::DESCRIPTION => $product->description,
            Product::PRICE => 15000,
            Product::CATEGORY_ID => $product->category_id,
        ])->assertStatus(200);

        $product->refresh();

        $this->assertStringContainsString('appendix', $product->name);
    }

    public function testUpdateProductAsNotOwner() {
        $user2 = User::factory()->createOne();
        $product = Product::factory()->createOne();

        Sanctum::actingAs($user2);

        $this->put("api/products/$product->id", [
            Product::NAME => $product->name . ' appendix',
            Product::DESCRIPTION => $product->description,
            Product::PRICE => 15000,
            Product::CATEGORY_ID => $product->category_id,
        ])->assertStatus(403);

        $product->refresh();

        $this->assertStringNotContainsString('appendix', $product->name);
    }

    public function testDeleteProductAsOwner() {
        $product = Product::factory()->createOne();

        Sanctum::actingAs($product->seller);

        $this->delete("api/products/$product->id")->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function testDeleteProductAsNotOwner() {
        $user2 = User::factory()->createOne();
        $product = Product::factory()->createOne();

        Sanctum::actingAs($user2);

        $this->delete("api/products/$product->id")->assertStatus(403);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }
}
