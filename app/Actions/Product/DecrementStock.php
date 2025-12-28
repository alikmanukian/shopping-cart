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
        throw_if($quantity < 1, InvalidArgumentException::class, 'Quantity must be at least 1.');

        if ($quantity > $product->stock_quantity) {
            throw new InvalidArgumentException(
                sprintf('Insufficient stock. Only %s available.', $product->stock_quantity)
            );
        }

        $product->decrement('stock_quantity', $quantity);

        /** @var Product $freshProduct */
        $freshProduct = $product->fresh();

        return $freshProduct;
    }
}
