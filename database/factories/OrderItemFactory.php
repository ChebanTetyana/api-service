<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;

class OrderItemFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'rental_expiration' => $this->faker->optional()->dateTimeBetween('+4 hours', '+24 hours'),
            'unique_code' => Str::uuid()->toString(),
        ];
    }
}
