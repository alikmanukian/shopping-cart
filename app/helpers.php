<?php

declare(strict_types=1);

if (! function_exists('moneyFormat')) {
    /**
     * @return non-empty-string
     */
    function moneyFormat(float|int|string $value): string
    {
        /** @var non-empty-string $symbol */
        $symbol = config('shop.currency_symbol');

        return $symbol.$value;
    }
}
