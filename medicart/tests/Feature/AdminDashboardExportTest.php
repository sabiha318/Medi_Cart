<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_collected_revenue_and_can_export_orders_excel(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        Order::factory()->create([
            'user_id' => $admin->id,
            'recipient_name' => 'Alice Customer',
            'shipping_address' => 'Test Street 1',
            'shipping_city' => 'Dhaka',
            'payment_method' => 'cod',
            'total_amount' => 150.00,
            'status' => 'delivered',
        ]);

        Order::factory()->create([
            'user_id' => $admin->id,
            'recipient_name' => 'Bob Customer',
            'shipping_address' => 'Test Street 2',
            'shipping_city' => 'Chattogram',
            'payment_method' => 'cod',
            'total_amount' => 250.50,
            'status' => 'pending',
        ]);

        Order::factory()->create([
            'user_id' => $admin->id,
            'recipient_name' => 'Carol Customer',
            'shipping_address' => 'Test Street 3',
            'shipping_city' => 'Sylhet',
            'payment_method' => 'sslcommerz',
            'total_amount' => 200.00,
            'status' => 'processing',
        ]);

        $dashboardResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('Collected Revenue');
        $dashboardResponse->assertSee('৳350.00');
        $dashboardResponse->assertSee('Pending COD');

        $exportResponse = $this->actingAs($admin)->get('/admin/orders/export');
        $exportResponse->assertOk();
        $exportResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $exportResponse->assertSee('Order ID');
    }

    public function test_admin_can_cancel_an_order_and_cancelled_orders_do_not_count_as_revenue(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin-cancel@example.com',
            'role' => 'admin',
        ]);

        $cancelledOrder = Order::factory()->create([
            'user_id' => $admin->id,
            'recipient_name' => 'Cancelled Customer',
            'shipping_address' => 'Cancelled Street',
            'shipping_city' => 'Dhaka',
            'payment_method' => 'cod',
            'total_amount' => 125.00,
            'status' => 'pending',
        ]);

        $paidOrder = Order::factory()->create([
            'user_id' => $admin->id,
            'recipient_name' => 'Paid Customer',
            'shipping_address' => 'Paid Street',
            'shipping_city' => 'Khulna',
            'payment_method' => 'bkash',
            'total_amount' => 99.99,
            'status' => 'processing',
        ]);

        $this->actingAs($admin)
            ->patch('/admin/orders/'.$cancelledOrder->id.'/status', ['status' => 'cancelled'])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', ['id' => $cancelledOrder->id, 'status' => 'cancelled']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertOk();
        $response->assertSee('৳99.99');
        $response->assertDontSee('Pending COD: ৳125.00');
    }
}
