<?php

namespace App\Http\Controllers;

use App\Contracts\IProductService;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;


class RentalController extends Controller
{
    private IProductService $productService;

    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }

    public function rent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'hours' => 'required|integer|min:1|max:24',
        ]);

        $user = auth()->user();

        if (!$user instanceof User) {
            return response()->json(['error' => 'Unauthorized user or invalid type.'], 401);
        }

        $result = $this->productService->rent($validated['product_id'], $validated['hours'], $user);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json($result, 201);
    }

    public function extendRental(Request $request, $orderId): JsonResponse
    {
        $validated = $request->validate([
            'hours' => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        if (!$user instanceof User) {
            return response()->json(['error' => 'Unauthorized user'], 401);
        }

        $order = Order::findOrFail($orderId);
        $result = $this->productService->extendRental($order, $validated['hours'], $user);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json($result, 200);
    }
}
