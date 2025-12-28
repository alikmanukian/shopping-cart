<?php

declare(strict_types=1);

namespace App\Actions\Cart;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use InvalidArgumentException;

final readonly class AddToCart
{
    public function __construct(private GetUserCart $getUserCart) {}

    /**
     * @throws InvalidArgumentException If insufficient stock
     */
    public function handle(User $user, Product $product, int $quantity = 1): CartItem
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException('Quantity must be at least 1.');
        }

        $cart = $this->getUserCart->handle($user);

        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        $totalQuantity = $existingItem ? $existingItem->quantity + $quantity : $quantity;

        if ($totalQuantity > $product->stock_quantity) {
            throw new InvalidArgumentException(
                "Insufficient stock. Only {$product->stock_quantity} available."
            );
        }

        if ($existingItem) {
            $existingItem->update(['quantity' => $totalQuantity]);

            return $existingItem->fresh();
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }
}
