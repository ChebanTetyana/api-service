<?php

namespace App\Services;

use App\Contracts\IProductService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class ProductService implements IProductService
{
    public function purchase(int $product_id, int $quantity, User $user): array
    {
        $product = Product::findOrFail($product_id);

        if (!$product) {
            return ['error' => 'Product not found.'];
        }

        if ($product->getAttribute('quantity') < $quantity) {
            return ['error' => 'Not enough stock available.'];
        }

        $totalPrice = $product->attributes['price'] * $quantity;

        if ($user->attributes['balance'] < $totalPrice) {
            return ['error' => 'Insufficient balance.'];
        }

        $product->attributes['quantity'] -= $quantity;
        $product->save();

        $user->attributes['balance'] -= $totalPrice;
        $user->save();

        $order = Order::create([
            'user_id' => $user->getAttribute('id'),
            'type' => 'purchase',
            'total_price' => $totalPrice,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->getAttribute('id'),
            'unique_code' => uniqid(),
        ]);

        return ['message' => 'Purchase successful!', 'order' => $order];
    }

    public function rent($product_id, int $hours, User $user): array
    {
        $product = Product::findOrFail($product_id);

        if (!$product) {
            return ['error' => 'Product not found.'];
        }

        if ($product->attributes['quantity'] < 1) {
            return ['error' => 'No stock available for rental.'];
        }

        $totalPrice = $product->getRentalPricePerHour() * $hours;

        if ($user->attributes['balance'] < $totalPrice) {
            return ['error' => 'Insufficient balance for rental.'];
        }

        $order = Order::create([
            'user_id' => $user->getAttribute('id'),
            'type' => 'rental',
            'total_price' => $totalPrice,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->getAttribute('id'),
            'rental_expiration' => now()->addHours($hours),
            'unique_code' => uniqid(),
        ]);

        $user->attributes['balance'] -= $totalPrice;
        $user->save();

        $product->attributes['quantity'] -= 1;
        $product->save();

        return ['message' => 'Rental successful!', 'order' => $order];
    }

    public function extendRental(Order $order, int $hours, User $user): array
    {
        $orderItem = $order->orderItems()->first();

        if ($order->attributes['type'] !== 'rental') {
            return ['error' => 'This order is not a rental.'];
        }

        $currentExpiration = $orderItem->rental_expiration;
        $newExpiration = $currentExpiration->copy()->addHours($hours);

        if ($newExpiration->gt($currentExpiration->copy()->addHours(24))) {
            return ['error' => 'Cannot extend rental beyond 24 hours from the current expiration.'];
        }

        $orderItem->update(['rental_expiration' => $newExpiration]);

        $additionalPrice = $orderItem->product->getRentalPricePerHour() * $hours;
        $order->update(['total_price' => $order->attributes['total_price'] + $additionalPrice]);

        if ($user->attributes['balance'] < $additionalPrice) {
            return ['error' => 'Insufficient balance to extend rental.'];
        }

        $user->update(['balance' => $user->attributes['balance'] - $additionalPrice]);

        return [
            'message' => 'Rental extended successfully.',
            'new_rental_expiration' => $newExpiration,
            'new_total_price' => $order->attributes['total_price'],
        ];
    }
}
