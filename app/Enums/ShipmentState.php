<?php

namespace App\Enums;

enum ShipmentState: int
{
    case Unpaid = 0;
    case Paid = 1;
    case PreparingForShipment = 2;
    case Shipped = 3;
    case FailedDelivery = 4;
    case Delivered = 5;
}
