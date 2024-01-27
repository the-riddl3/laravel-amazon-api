<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\ProductPurchase;
use App\Models\User;

class ProductShipmentPolicy
{
    public function store(User $user, ProductPurchase $purchase): bool
    {
        return $user->id === $purchase->buyer_id || $user->role === UserRole::Admin;
    }

    public function index(User $user, ProductPurchase $purchase): bool
    {
        return $user->id === $purchase->buyer_id || $user->role === UserRole::Admin;
    }
}
