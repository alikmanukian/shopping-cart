<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;

final class CartItemPolicy
{
    /**
     * Determine whether the user can update the cart item.
     */
    public function update(User $user, CartItem $cartItem): bool
    {
        return $cartItem->cart->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the cart item.
     */
    public function delete(User $user, CartItem $cartItem): bool
    {
        return $cartItem->cart->user_id === $user->id;
    }
}
