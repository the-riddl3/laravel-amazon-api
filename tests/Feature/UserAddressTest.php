<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserAddressTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreAddress() {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->post("/api/auth/user/addresses", [
            'address_line' => 'test address line'
        ])->assertStatus(201);

        $this->assertDatabaseCount('user_addresses', 1);
    }

    public function testIndexAddress() {
        $address = UserAddress::factory()->create();
        $user = $address->user;

        Sanctum::actingAs($user);

        $this->get("/api/auth/user/addresses")
            ->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function testShowAddress() {
        $address = UserAddress::factory()->create();
        $user = $address->user;

        Sanctum::actingAs($user);

        $this->get("/api/auth/user/addresses/$address->id")
            ->assertStatus(200)
            ->assertJsonFragment([
                'address_line' => $address->address_line,
            ]);
    }

    public function testShowAddressUnauthorized() {
        $address = UserAddress::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->get("/api/auth/user/addresses/$address->id")
            ->assertStatus(403);
    }

    public function testDeleteAddress() {
        $address = UserAddress::factory()->create();
        $user = $address->user;

        Sanctum::actingAs($user);

        $this->delete("/api/auth/user/addresses/$address->id")
            ->assertStatus(200);
    }

    public function testDeleteAddressUnauthorized() {
        $address = UserAddress::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->delete("/api/auth/user/addresses/$address->id")
            ->assertStatus(403);
    }
}
