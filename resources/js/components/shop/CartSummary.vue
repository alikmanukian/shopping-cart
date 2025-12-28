<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { index as checkoutIndex } from '@/routes/checkout';
import type { Cart } from '@/types/shop';
import { Link } from '@inertiajs/vue3';

interface Props {
    cart: Cart;
    showCheckoutButton?: boolean;
}

withDefaults(defineProps<Props>(), {
    showCheckoutButton: true,
});
</script>

<template>
    <div class="rounded-xl bg-shop-cream p-6">
        <div class="space-y-4">
            <div class="flex items-center justify-between text-sm">
                <span class="text-shop-text-light"> Subtotal </span>
                <span class="font-semibold text-shop-text"
                    >${{ cart.subtotal }}</span
                >
            </div>

            <div class="flex items-center justify-between text-sm">
                <span class="text-shop-text-light">Shipping</span>
                <span class="font-medium text-shop-success">Free</span>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <div class="flex items-center justify-between">
                    <span class="text-base font-semibold text-shop-text">
                        Total
                    </span>
                    <span class="text-xl font-bold text-shop-text"
                        >${{ cart.subtotal }}</span
                    >
                </div>
            </div>
        </div>

        <div v-if="showCheckoutButton" class="mt-6">
            <Link :href="checkoutIndex().url">
                <Button
                    class="w-full rounded-full bg-shop-blue hover:bg-shop-blue-dark"
                    size="lg"
                >
                    <span>Continue to checkout</span>
                </Button>
            </Link>
        </div>
    </div>
</template>
