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
        throw_if($quantity < 1, InvalidArgumentException::class, 'Quantity must be at least 1.');

        $cartItem->loadMissing('product');

        $product = $cartItem->product;
        throw_if($product === null, InvalidArgumentException::class, 'Product not found.');

        if ($quantity > $product->stock_quantity) {
            throw new InvalidArgumentException(
                sprintf('Insufficient stock. Only %s available.', $product->stock_quantity)
            );
        }

        $cartItem->update(['quantity' => $quantity]);

        /** @var CartItem $freshItem */
        $freshItem = $cartItem->fresh();

        return $freshItem;
    }
}
