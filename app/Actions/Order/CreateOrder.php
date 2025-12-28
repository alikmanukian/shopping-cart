<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Actions\Cart\ClearCart;
use App\Actions\Product\CheckLowStock;
use App\Actions\Product\DecrementStock;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

final readonly class CreateOrder
{
    public function __construct(
        private DecrementStock $decrementStock,
        private CheckLowStock $checkLowStock,
        private ClearCart $clearCart,
    ) {}

    /**
     * @throws InvalidArgumentException If cart is empty or has insufficient stock
     * @throws Throwable
     */
    public function handle(User $user, Cart $cart): Order
    {
        $cart->loadMissing(['items.product']);

        if ($cart->items->isEmpty()) {
            throw new InvalidArgumentException('Cart is empty.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock_quantity) {
                throw new InvalidArgumentException(
                    "Insufficient stock for {$item->product->name}. Only {$item->product->stock_quantity} available."
                );
            }
        }

        return DB::transaction(function () use ($user, $cart): Order {
            $subtotal = $cart->items->sum(fn ($item) => $item->quantity * $item->product->price);

            $order = Order::create([
                'user_id' => $user->id,
                'status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'product_price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->quantity * $item->product->price,
                ]);

                $this->decrementStock->handle($item->product, $item->quantity);
                $this->checkLowStock->handle($item->product->fresh());
            }

            $this->clearCart->handle($cart);

            $order->markAsCompleted();

            return $order->fresh()?->load('items');
        });
    }
}
