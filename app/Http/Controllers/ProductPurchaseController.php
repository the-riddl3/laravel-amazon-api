<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductPurchaseResource;
use App\Models\ProductPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductPurchaseController extends Controller
{
    public function store(Request $request): ProductPurchaseResource
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|min:1',
        ]);

        $purchase = ProductPurchase::query()->create([...$validated, 'buyer_id' => Auth::id()]);

        return new ProductPurchaseResource($purchase);
    }

    public function show(ProductPurchase $purchase): ProductPurchaseResource
    {
        return new ProductPurchaseResource($purchase);
    }
}
