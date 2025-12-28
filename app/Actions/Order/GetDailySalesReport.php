<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

final readonly class GetDailySalesReport
{
    /**
     * @return array<string, mixed>
     */
    public function handle(?Carbon $date = null): array
    {
        $date ??= Date::today();

        $orders = Order::query()
            ->completed()
            ->whereDate('completed_at', $date)
            ->with('items')
            ->get();

        /** @var float $totalRevenue */
        $totalRevenue = $orders->sum('total');
        $totalItemsSold = $orders->flatMap->items->sum('quantity');

        $topProducts = $orders->flatMap->items
            ->groupBy('product_name')
            ->map(fn (Collection $items, string $productName): array => [
                'name' => $productName,
                'quantity' => $items->sum('quantity'),
                'revenue' => $items->sum('subtotal'),
            ])
            ->sortByDesc('quantity')
            ->take(10)
            ->values();

        return [
            'date' => $date->format('Y-m-d'),
            'total_orders' => $orders->count(),
            'total_revenue' => number_format($totalRevenue, 2),
            'total_items_sold' => $totalItemsSold,
            'orders' => $orders,
            'top_products' => $topProducts,
        ];
    }
}
