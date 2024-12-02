<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController
{
    public function index(): JsonResponse
    {
        $products = Product::all();

        return response()->json($products);
    }

    public function show($product_id): JsonResponse
    {
        $product = Product::find($product_id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }


}
