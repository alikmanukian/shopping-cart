<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use App\Models\CartItem;
use InvalidArgumentException;

final readonly class UpdateCartItem
{
    /**
     * @throws InvalidArgumentException If insufficient stock or invalid quantity
     */
    public function handle(CartItem $cartItem, int $quantity): CartItem
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException('Quantity must be at least 1.');
        }

        $product = $cartItem->product;

        if ($quantity > $product->stock_quantity) {
            throw new InvalidArgumentException(
                "Insufficient stock. Only {$product->stock_quantity} available."
            );
        }

        $cartItem->update(['quantity' => $quantity]);

        return $cartItem->fresh();
    }
}
