<?php

namespace App\Http\Controllers;

use App\Enums\ShipmentState;
use App\Http\Resources\ProductShipmentStatusResource;
use App\Models\ProductPurchase;
use App\Models\ProductShipmentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class ProductShipmentStatusController extends Controller
{
    public function store(ProductPurchase $purchase, Request $request): ProductShipmentStatusResource
    {
        $validated = $request->validate([
            'state' => ['required', Rule::enum(ShipmentState::class)],
            'time' => 'date',
            'message' => 'string|nullable'
        ]);

        return new ProductShipmentStatusResource(
            ProductShipmentStatus::query()->create([...$validated, 'purchase_id' => $purchase->id])
        );
    }

    public function index(ProductPurchase $purchase): AnonymousResourceCollection
    {
        return ProductShipmentStatusResource::collection($purchase->shipmentStatuses);
    }
}
