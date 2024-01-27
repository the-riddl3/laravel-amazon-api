<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\ProductPurchase;
use App\Models\User;

class ProductShipmentStatusPolicy
{
    public function index(User $user, ProductPurchase $purchase): bool
    {
        return $purchase->buyer_id === $user->id || in_array($user->role, [UserRole::Admin, UserRole::ShipmentDeliveryWorker]);
    }

    public function store(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::ShipmentDeliveryWorker]);
    }
}
