<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileDefaultAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_address_details_are_displayed_on_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'User Name',
            'email' => 'profile@example.com',
            'phone' => '01700000000',
            'role' => 'customer',
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'name' => 'Shipping Recipient',
            'phone' => '01711111111',
            'address' => 'Old address line',
            'city' => 'Old City',
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)->get('/profile');
        $response->assertOk();
        $response->assertSee('Default Shipping Name');
        $response->assertSee('Shipping Recipient');
        $response->assertSee('01711111111');
        $response->assertSee('Old address line');

        $address->update([
            'name' => 'Updated Recipient',
            'phone' => '01722222222',
            'address' => 'Updated address line',
            'city' => 'Updated City',
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)->get('/profile');
        $response->assertOk();
        $response->assertSee('Default Shipping Name');
        $response->assertSee('Updated Recipient');
        $response->assertSee('01722222222');
        $response->assertSee('Updated address line');
    }
}
