<?php

namespace App\Services;

use App\Enums\ShipmentState;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductShipment;
use App\Models\UserAddress;

interface ShippingService
{
    public function startShippingProcess(ProductPurchase $purchase, UserAddress $address);
    public function updateShipmentState(ProductShipment $shipment, ShipmentState $state, ?string $message);
    public function getLastStatus(ProductShipment $shipment);
}
