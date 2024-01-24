<?php

namespace App\Services;

use App\Enums\ShipmentState;
use App\Models\ProductPurchase;
use App\Models\ProductShipmentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ShippingServiceInHouse implements ShippingService
{
    public function startShippingProcess(ProductPurchase $purchase): Model|Builder
    {
        return ProductShipmentStatus::query()->create([
            'state' => ShipmentState::Unpaid->value,
            'time' => now(),
            'purchase_id' => $purchase->id
        ]);
    }

    public function updateShipmentState(ProductPurchase $purchase, ShipmentState $state, ?string $message): Builder|Model
    {
        return ProductShipmentStatus::query()->create([
            'state' => $state->value,
            'message' => $message,
            'time' => now(),
            'purchase_id' => $purchase->id,
        ]);
    }

    public function getLastStatus(ProductPurchase $purchase): Model|Builder|null
    {
        return ProductShipmentStatus::query()
            ->where('purchase_id', $purchase->id)
            ->orderByDesc('time')
            ->first();
    }
}
