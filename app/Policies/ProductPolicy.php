<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(?User $user = null): bool
    {
        return true;
    }

    public function create(?User $user = null): bool
    {
        return auth()->check();
    }

    public function view(?User $user = null, Product $product): bool
    {
        return true;
    }

    public function update(?User $user = null, Product $product): bool
    {
        return auth()->check();
    }

    public function delete(?User $user = null, Product $product): bool
    {
        return auth()->check();
    }
}
