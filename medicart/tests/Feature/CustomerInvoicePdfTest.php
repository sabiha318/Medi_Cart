<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerInvoicePdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_download_invoice_pdf_for_their_order(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $medicine = Medicine::factory()->create([
            'name' => 'Vitamin C',
            'price' => 100.00,
            'stock' => 50,
        ]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'recipient_name' => 'Alice Customer',
            'recipient_phone' => '01712345678',
            'shipping_address' => '12 Main Road',
            'shipping_city' => 'Dhaka',
            'subtotal' => 250.00,
            'delivery_fee' => 50.00,
            'total_amount' => 300.00,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'medicine_id' => $medicine->id,
            'medicine_name' => 'Vitamin C',
            'price' => 100.00,
            'quantity' => 2,
            'subtotal' => 200.00,
        ]);

        $response = $this->actingAs($user)->get('/orders/'.$order->id.'/invoice');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
