<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function create(ProductRequest $request): JsonResponse
    {
        $product = new Product($request->only(['name', 'description', 'price', 'category_id']));
        $product->seller()->associate(Auth::user());
        $product->save();

        return response()->json([
            'product' => $product,
            'message' => 'Product created successfully',
        ]);
    }

    public function index(): JsonResponse
    {
        // recommendation system TODO
        return response()->json(['products' => Product::all()]);
    }

    public function read(Product $product): JsonResponse
    {
        return response()->json(['product' => $product]);
    }

    public function update(ProductStoreRequest $request, Product $product): JsonResponse
    {
        Gate::authorize('store', $product);

        $product->update($request->only(['name', 'description', 'price', 'category_id']));

        return response()->json(['message' => 'product updated']);
    }

    public function delete(Product $product): JsonResponse
    {
        Gate::authorize('store', $product);

        $product->delete();

        return response()->json(['message' => 'Product has been deleted']);
    }
}
