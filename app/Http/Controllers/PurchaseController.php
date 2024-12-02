<?php

namespace App\Http\Controllers;

use App\Contracts\IProductService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private IProductService $productService;

    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }

    public function purchase(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        if (!$user instanceof User) {
            return response()->json(['error' => 'Unauthorized user'], 401);
        }

        $result = $this->productService->purchase($validated['product_id'], $validated['quantity'], $user);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json($result, 201);
    }
}
