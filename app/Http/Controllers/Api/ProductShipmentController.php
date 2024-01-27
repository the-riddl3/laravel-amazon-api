<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductShipmentResource;
use App\Models\ProductPurchase;
use App\Models\ProductShipment;
use App\Models\UserAddress;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ProductShipmentController extends Controller
{
    public function store(ProductPurchase $purchase, Request $request): ProductShipmentResource
    {
        $validated = $request->validate([
            'user_address_id' => [function (string $attribute, mixed $value, Closure $fail) {
                /** @var UserAddress $address */
                $address = UserAddress::query()->find($value);
                if($address->user_id !== Auth::id()) {
                    $fail("this address does not belong to the authenticated user");
                }
            }],
        ]);

        $shipment = ProductShipment::query()->create([
            ProductShipment::PURCHASE_ID => $purchase->id,
            ProductShipment::USER_ADDRESS_ID => $validated['user_address_id'],
        ]);
        $shipment->refresh();

        return new ProductShipmentResource($shipment);
    }

    public function index(ProductPurchase $purchase): AnonymousResourceCollection
    {
        return ProductShipmentResource::collection($purchase->shipments);
    }
}
