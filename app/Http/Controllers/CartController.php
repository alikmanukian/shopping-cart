<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Cart\AddToCart;
use App\Actions\Cart\GetUserCart;
use App\Actions\Cart\RemoveFromCart;
use App\Actions\Cart\UpdateCartItem;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use InvalidArgumentException;

final class CartController extends Controller
{
    public function __construct(
        private readonly GetUserCart $getUserCart,
        private readonly AddToCart $addToCart,
        private readonly UpdateCartItem $updateCartItem,
        private readonly RemoveFromCart $removeFromCart,
    ) {}

    public function index(#[CurrentUser] User $user): Response
    {
        $cart = $this->getUserCart->handle($user);

        return Inertia::render('Cart/Index', [
            'cart' => $cart,
            'items' => $cart->items,
        ]);
    }

    public function store(#[CurrentUser] User $user, AddToCartRequest $request): RedirectResponse
    {
        $product = Product::query()->findOrFail($request->integer('product_id'));

        try {
            $this->addToCart->handle(
                $user,
                $product,
                $request->integer('quantity')
            );

            return back()->with('success', $product->name.' added to cart.');
        } catch (InvalidArgumentException $invalidArgumentException) {
            return back()->withErrors(['quantity' => $invalidArgumentException->getMessage()]);
        }
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        try {
            $this->updateCartItem->handle($cartItem, $request->integer('quantity'));

            return back()->with('success', 'Cart updated.');
        } catch (InvalidArgumentException $invalidArgumentException) {
            return back()->withErrors(['quantity' => $invalidArgumentException->getMessage()]);
        }
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->authorize('delete', $cartItem);

        $productName = $cartItem->product->name ?? 'Product';

        $this->removeFromCart->handle($cartItem);

        return back()->with('success', $productName.' removed from cart.');
    }
}
