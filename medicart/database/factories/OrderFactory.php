<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipient_name' => $this->faker->name(),
            'recipient_phone' => $this->faker->phoneNumber(),
            'shipping_address' => $this->faker->streetAddress(),
            'shipping_city' => $this->faker->city(),
            'delivery_option' => 'standard',
            'delivery_fee' => 50.00,
            'payment_method' => 'cod',
            'subtotal' => 100.00,
            'total_amount' => 150.00,
            'status' => 'pending',
        ];
    }
}
