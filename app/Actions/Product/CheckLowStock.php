<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Jobs\SendLowStockNotification;
use App\Models\Product;

final readonly class CheckLowStock
{
    /**
     * Check if product stock is low and dispatch notification if needed.
     *
     * @return bool Whether stock is low
     */
    public function handle(Product $product): bool
    {
        if (! $product->is_low_stock) {
            return false;
        }

        SendLowStockNotification::dispatch($product);

        return true;
    }
}
