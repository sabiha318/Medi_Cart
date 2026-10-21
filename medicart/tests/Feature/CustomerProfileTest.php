<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_their_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Alice Customer',
            'email' => 'alice@example.com',
            'phone' => '01712345678',
            'role' => 'customer',
        ]);

        $user->addresses()->create([
            'name' => 'Alice Customer',
            'phone' => '01712345678',
            'address' => '12 Main Road, Dhaka',
            'city' => 'Dhaka',
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSee('Alice Customer');
        $response->assertSee('alice@example.com');
        $response->assertSee('01712345678');
        $response->assertSee('12 Main Road, Dhaka');
        $response->assertSee('Profile');
    }

    public function test_customer_can_update_their_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Alice Customer',
            'email' => 'alice@example.com',
            'phone' => '01712345678',
            'role' => 'customer',
        ]);

        $user->addresses()->create([
            'name' => 'Alice Customer',
            'phone' => '01712345678',
            'address' => '12 Main Road, Dhaka',
            'city' => 'Dhaka',
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Alice Updated',
            'email' => 'alice.updated@example.com',
            'phone' => '1987654321',
            'shipping_address' => '45 New Street, Chattogram',
            'shipping_city' => 'Chattogram',
        ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Alice Updated',
            'email' => 'alice.updated@example.com',
            'phone' => '+8801987654321',
        ]);
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'address' => '45 New Street, Chattogram',
            'city' => 'Chattogram',
            'is_default' => true,
        ]);
    }
}
