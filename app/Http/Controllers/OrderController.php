<?php

namespace App\Http\Controllers;

use App\Contracts\IProductService;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private IProductService $productService;

    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }

    public function status(int $order_id): JsonResponse
    {
        try {
            $order = Order::with('orderItems.product')->findOrFail($order_id);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        $orderItem = $order->orderItems()->first();
        $status = $order->type === 'purchase' ? 'Purchased' : 'Rented';

        return response()->json([
            'order_id' => $order->id,
            'status' => $status,
            'product' => $orderItem->product->name,
            'unique_code' => $orderItem->unique_code,
            'rental_expiration' => $order->type === 'rental' ? $orderItem->rental_expiration : null,
        ]);
    }

    public function extendRental(int $order_id, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hours' => 'required|integer|min:1|max:24',
        ]);

        $order = Order::findOrFail($order_id);
        $user = auth()->user();

        if (!$user instanceof User) {
            return response()->json(['error' => 'Unauthorized user'], 401);
        }

        $order->orderItems()->first();

        if ($order->type !== 'rental') {
            return response()->json(['error' => 'This order is not a rental.'], 400);
        }

        $result = $this->productService->extendRental($order, $validated['hours'], $user);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json([
            'message' => $result['message'],
            'new_rental_expiration' => $result['new_rental_expiration'],
            'new_total_price' => $result['new_total_price'],
        ], 200);
    }

    public function history(): JsonResponse
    {
        $user = auth()->user();

        $orders = $user->orders()->with('orderItems.product')->get();

        return response()->json([
            'user_id' => $user->attributes['id'],
            'orders' => $orders,
        ]);
    }
}
