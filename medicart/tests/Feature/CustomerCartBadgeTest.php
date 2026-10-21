<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCartBadgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cart_badge_shows_number_of_items_in_cart(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $medicine = Medicine::factory()->create([
            'name' => 'Vitamin C',
            'price' => 120.00,
            'stock' => 20,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'medicine_id' => $medicine->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('2');
        $response->assertSee('items in cart');
    }
}
