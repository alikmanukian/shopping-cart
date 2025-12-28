<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Cart\GetUserCart;
use App\Actions\Order\CreateOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use InvalidArgumentException;
use Throwable;

final class CheckoutController extends Controller
{
    public function __construct(
        private readonly GetUserCart $getUserCart,
        private readonly CreateOrder $createOrder,
    ) {}

    public function index(#[CurrentUser] User $user): Response
    {
        $cart = $this->getUserCart->handle($user);

        return Inertia::render('Checkout/Index', [
            'cart' => $cart,
            'items' => $cart->items->loadMissing('product'),
            'isEmpty' => $cart->items->isEmpty(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(#[CurrentUser] User $user): RedirectResponse
    {
        $cart = $this->getUserCart->handle($user);

        try {
            $order = $this->createOrder->handle($user, $cart);

            return to_route('checkout.success', $order)
                ->with('success', 'Order placed successfully!');
        } catch (InvalidArgumentException $invalidArgumentException) {
            return back()->withErrors(['checkout' => $invalidArgumentException->getMessage()]);
        }
    }

    public function success(#[CurrentUser] User $user, Order $order): Response
    {
        abort_unless($order->user_id === $user->id, 403);

        return Inertia::render('Checkout/Success', [
            'order' => $order->load('items.product'),
        ]);
    }
}
