<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\User;

final class GetUserCart
{
    /**
     * Get or create the user's cart with items and products.
     */
    public function handle(User $user): Cart
    {
        return $user->cart()->firstOrCreate([])->loadMissing(['items.product']);
    }
}
