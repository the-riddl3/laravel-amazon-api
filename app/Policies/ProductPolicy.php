<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function store(User $user, Product $product): bool
    {
        return $product->seller->id === $user->id;
    }
}
