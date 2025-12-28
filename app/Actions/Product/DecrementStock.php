<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Product;
use InvalidArgumentException;

final readonly class DecrementStock
{
    /**
     * @throws InvalidArgumentException If insufficient stock
     */
    public function handle(Product $product, int $quantity): Product
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException('Quantity must be at least 1.');
        }

        if ($quantity > $product->stock_quantity) {
            throw new InvalidArgumentException(
                "Insufficient stock. Only {$product->stock_quantity} available."
            );
        }

        $product->decrement('stock_quantity', $quantity);

        return $product->fresh();
    }
}
