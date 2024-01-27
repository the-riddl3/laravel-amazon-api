<?php

namespace App\Services;

use App\Enums\ShipmentState;
use App\Models\ProductPurchase;
use App\Models\ProductShipment;
use App\Models\ProductShipmentStatus;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ShippingServiceInHouse implements ShippingService
{
    public function startShippingProcess(ProductPurchase $purchase, UserAddress $address): Model|Builder
    {
        /** @var ProductShipment $shipment */
        $shipment = ProductShipment::query()->create([
            ProductShipment::PURCHASE_ID => $purchase->id,
            ProductShipment::USER_ADDRESS_ID => $address->id,
        ]);
        $shipment->refresh();
        return ProductShipmentStatus::query()->create([
            ProductShipmentStatus::STATE => ShipmentState::Unpaid->value,
            ProductShipmentStatus::TIME => now(),
            ProductShipmentStatus::SHIPMENT_ID => $shipment->id,
        ]);
    }

    public function updateShipmentState(ProductShipment $shipment, ShipmentState $state, ?string $message): Builder|Model
    {
        return ProductShipmentStatus::query()->create([
            ProductShipmentStatus::STATE => $state->value,
            ProductShipmentStatus::MESSAGE => $message,
            ProductShipmentStatus::TIME => now(),
            ProductShipmentStatus::SHIPMENT_ID => $shipment->id,
        ]);
    }

    public function getLastStatus(ProductShipment $shipment): Model|Builder|null
    {
        return ProductShipmentStatus::query()
            ->where(ProductShipmentStatus::SHIPMENT_ID, $shipment->id)
            ->orderByDesc(ProductShipmentStatus::TIME)
            ->first();
    }
}
