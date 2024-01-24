<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case User = 'user';
    case ShipmentDeliveryWorker = 'shipment_delivery_worker';
}
