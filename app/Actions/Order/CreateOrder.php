<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Actions\Cart\ClearCart;
use App\Actions\Product\CheckLowStock;
use App\Actions\Product\DecrementStock;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
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

        throw_if($cart->items->isEmpty(), InvalidArgumentException::class, 'Cart is empty.');

        foreach ($cart->items as $item) {
            $product = $item->product;
            throw_if($product === null, InvalidArgumentException::class, 'Product not found.');

            if ($item->quantity > $product->stock_quantity) {
                throw new InvalidArgumentException(
                    sprintf('Insufficient stock for %s. Only %s available.', $product->name, $product->stock_quantity)
                );
            }
        }

        return DB::transaction(function () use ($user, $cart): Order {
            $subtotal = $cart->items->sum(function (CartItem $item): float {
                /** @var Product $product */
                $product = $item->product;

                return $item->quantity * $product->price;
            });

            $order = Order::query()->create([
                'user_id' => $user->id,
                'status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            foreach ($cart->items as $item) {
                /** @var Product $product */
                $product = $item->product;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->quantity * $product->price,
                ]);

                $this->decrementStock->handle($product, $item->quantity);

                $freshProduct = $product->fresh();
                if ($freshProduct !== null) {
                    $this->checkLowStock->handle($freshProduct);
                }
            }

            $this->clearCart->handle($cart);

            $order->markAsCompleted();

            /** @var Order $freshOrder */
            $freshOrder = $order->fresh();

            return $freshOrder->load('items');
        });
    }
}
