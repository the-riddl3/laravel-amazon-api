<?php

namespace App\Services;

use App\Enums\ShipmentState;
use App\Models\Product;
use App\Models\ProductPurchase;

interface ShippingService
{
    public function startShippingProcess(ProductPurchase $purchase);
    public function updateShipmentState(ProductPurchase $purchase, ShipmentState $state, ?string $message);
    public function getLastStatus(ProductPurchase $purchase);
}
