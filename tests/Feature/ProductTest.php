<?php

namespace Tests\Feature;

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
            Product::PRICE => 15000
        ])->assertStatus(200);


        /** @var Product $product */
        $product = Product::query()->first();

        $this->assertEquals('Cheap Laptop', $product->name);
        $this->assertEquals('Works, I think...', $product->description);
        $this->assertEquals(15000, $product->price);
    }

    public function testCreateProductUnauthenticated() {
        $this->post('/api/products', [
            Product::NAME => 'Cheap Laptop',
            Product::DESCRIPTION => 'Works, I think...',
            Product::PRICE => 15000
        ])->assertStatus(302);
    }

    public function testReadProduct() {
        $user = $this->createDummyUser();
        $product = $this->createDummyProduct($user->id);

        $this->get("api/products/$product->id")->assertStatus(200);
    }

    public function testUpdateProductAsOwner()
    {
        $user = $this->createDummyUser();
        $product = $this->createDummyProduct($user->id);

        Sanctum::actingAs($user);

        $this->put("api/products/$product->id", [
            Product::NAME => $product->name . ' appendix',
            Product::DESCRIPTION => $product->description,
            Product::PRICE => 15000
        ])->assertStatus(200);

        $product->refresh();

        $this->assertStringContainsString('appendix', $product->name);
    }

    public function testUpdateProductAsNotOwner() {
        $user = $this->createDummyUser();
        $user2 = $this->createDummyUser();
        $product = $this->createDummyProduct($user->id);

        Sanctum::actingAs($user2);

        $this->put("api/products/$product->id", [
            Product::NAME => $product->name . ' appendix',
            Product::DESCRIPTION => $product->description,
            Product::PRICE => 15000
        ])->assertStatus(403);

        $product->refresh();

        $this->assertStringNotContainsString('appendix', $product->name);
    }

    public function testDeleteProductAsOwner() {
        $user = $this->createDummyUser();
        $product = $this->createDummyProduct($user->id);

        Sanctum::actingAs($user);

        $this->delete("api/products/$product->id")->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function testDeleteProductAsNotOwner() {
        $user = $this->createDummyUser();
        $user2 = $this->createDummyUser();
        $product = $this->createDummyProduct($user->id);

        Sanctum::actingAs($user2);

        $this->delete("api/products/$product->id")->assertStatus(403);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    private function createDummyProduct(int $seller_id): Product
    {
        /** @var Product $product */
        $product = Product::query()->create([
            Product::NAME => 'Cheap Laptop',
            Product::DESCRIPTION => 'Works, I think...',
            Product::PRICE => 15000,
            Product::USER_ID => $seller_id
        ]);

        return $product;
    }

    private function createDummyUser(): User
    {
        return User::factory()->createOne();
    }
}
