<?php

namespace App\Http\Controllers\Api;

use App\Dtos\BrowsingHistoryEntryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = new Product($request->only(['name', 'description', 'price', 'category_id']));
        $product->seller()->associate(Auth::user());
        $product->save();

        return response()->json([
            'product' => $product,
            'message' => 'Product created successfully',
        ]);
    }

    public function index(ProductRequest $request): JsonResponse
    {
        $filters = $request->getFilters();
        if (!$filters) {
            // recommendation system TODO
            return response()->json(['products' => Product::all()]);
        }

        $query = Product::query();
        foreach ($filters as $field => $value) {
            $query->where($field, 'LIKE', "%$value%");
        }
        $products = $query->get();

        return response()->json(['products' => $products]);
    }

    public function show(Product $product): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        // add product to user's browsing history after responding to the client with the product data
        // (^ this is done to ensure that response time is not slowed down because of history collection)
        dispatch(function () use(&$user, &$product) {
            $user->getBrowsingHistoryService()
                ->addHistoryToCache(
                    new BrowsingHistoryEntryDto(
                        $product->name,
                        // TODO - implement product image
                        '',
                        route('products.show', ['product' => $product->id])
                    ));
        })->afterResponse();
        return response()->json(['product' => $product]);
    }

    public function update(ProductStoreRequest $request, Product $product): JsonResponse
    {
        Gate::authorize('store', $product);
        $product->update($request->only(['name', 'description', 'price', 'category_id']));

        return response()->json(['message' => 'product updated']);
    }

    public function destroy(Product $product): JsonResponse
    {
        Gate::authorize('store', $product);
        $product->delete();

        return response()->json(['message' => 'Product has been deleted']);
    }

}
