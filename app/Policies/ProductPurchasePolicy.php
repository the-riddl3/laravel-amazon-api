<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\ProductPurchase;
use App\Models\User;

class ProductPurchasePolicy
{
    public function show(User $user, ProductPurchase $purchase): bool
    {
        return $purchase->buyer_id === $user->id || $user->role === UserRole::Admin;
    }
}
