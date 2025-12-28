<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use App\Models\Cart;

final readonly class ClearCart
{
    public function handle(Cart $cart): bool
    {
        return $cart->items()->delete() >= 0;
    }
}
