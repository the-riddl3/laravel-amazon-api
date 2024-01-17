<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreCategory() {
        Sanctum::actingAs(User::factory()->admin()->createOne());

        $this->post('/api/categories/', [
            Category::NAME => 'Technology'
        ])->assertStatus(200);

        $this->assertDatabaseCount('categories', 1);
    }

    public function testStoreCategoryUnauthenticated() {
        $this->post('/api/categories/', [
            Category::NAME => 'Technology'
        ])->assertStatus(302);

        $this->assertDatabaseCount('categories', 0);
    }

    public function testStoreCategoryUnauthorized() {
        Sanctum::actingAs(User::factory()->createOne());

        $this->post('/api/categories/', [
            Category::NAME => 'Technology'
        ])->assertStatus(403);

        $this->assertDatabaseCount('categories', 0);
    }

    public function testShowCategory() {
        $category = Category::factory()->createOne();

        $this->get("/api/categories/$category->id")->assertStatus(200);
    }

    public function testIndexCategory() {
        Category::factory()->createOne();

        $this->get('/api/categories')->assertJsonCount(1, 'categories');
    }

    public function testUpdateCategory() {
        $category = Category::factory()->createOne();
        Sanctum::actingAs(User::factory()->admin()->createOne());

        $this->put("/api/categories/$category->id", [
            Category::NAME => $category->name . " appendix"
        ])->assertStatus(200);

        $category->refresh();

        $this->assertStringContainsString('appendix', $category->name);
    }


    public function testUpdateCategoryUnauthenticated() {
        $category = Category::factory()->createOne();

        $this->put("/api/categories/$category->id", [
            Category::NAME => $category->name . " appendix"
        ])->assertStatus(302);

        $category->refresh();

        $this->assertStringNotContainsString('appendix', $category->name);
    }

    public function testUpdateCategoryUnauthorized() {
        $category = Category::factory()->createOne();
        Sanctum::actingAs(User::factory()->createOne());

        $this->put("/api/categories/$category->id", [
            Category::NAME => $category->name . " appendix"
        ])->assertStatus(403);

        $category->refresh();

        $this->assertStringNotContainsString('appendix', $category->name);
    }
}
