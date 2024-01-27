<?php

namespace App\Http\Controllers\Api;

use App\Enums\ShipmentState;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductShipmentStatusResource;
use App\Models\ProductPurchase;
use App\Models\ProductShipment;
use App\Models\ProductShipmentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class ProductShipmentStatusController extends Controller
{
    public function store(ProductPurchase $purchase, ProductShipment $shipment, Request $request): ProductShipmentStatusResource
    {
        $validated = $request->validate([
            'state' => ['required', Rule::enum(ShipmentState::class)],
            'time' => 'date',
            'message' => 'string|nullable'
        ]);

        return new ProductShipmentStatusResource(
            ProductShipmentStatus::query()->create([...$validated, ProductShipmentStatus::SHIPMENT_ID => $shipment->id])
        );
    }

    public function index(ProductPurchase $purchase, ProductShipment $shipment): AnonymousResourceCollection
    {
        return ProductShipmentStatusResource::collection($shipment->statuses);
    }
}
