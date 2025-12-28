<script setup lang="ts">
import EmptyState from '@/components/shop/EmptyState.vue';
import Button from '@/components/ui/button/Button.vue';
import ShopLayout from '@/layouts/shop/ShopLayout.vue';
import { home } from '@/routes';
import { index as cartIndex } from '@/routes/cart';
import { store as checkoutStore } from '@/routes/checkout';
import type { Cart, CartItem } from '@/types/shop';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Loader2, Lock } from 'lucide-vue-next';

interface Props {
    cart: Cart;
    items: CartItem[];
    isEmpty: boolean;
}

defineProps<Props>();

const form = useForm({});

const handlePlaceOrder = () => {
    form.post(checkoutStore().url);
};
</script>

<template>
    <Head title="Complete your order" />

    <ShopLayout>
        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1
                    class="font-display text-2xl font-bold text-shop-text md:text-3xl"
                >
                    Complete your order
                </h1>
                <p class="mt-2 text-shop-text-light">
                    Have an account already?
                    <Link
                        href="/login"
                        class="font-medium text-shop-blue hover:text-shop-blue-dark"
                    >
                        Login
                    </Link>
                </p>
            </div>

            <div v-if="!isEmpty">
                <div class="grid gap-8 lg:grid-cols-5">
                    <!-- Left Column - Checkout Form -->
                    <div class="lg:col-span-3">
                        <div class="space-y-6">
                            <!-- Demo Notice -->
                            <div class="rounded-xl bg-shop-blue/5 p-6">
                                <h2
                                    class="text-sm font-semibold tracking-wider text-shop-text-light uppercase"
                                >
                                    Demo Checkout
                                </h2>
                                <p class="mt-2 text-sm text-shop-text-light">
                                    This is a demonstration checkout. Click
                                    "Place Order" to complete your order
                                    instantly.
                                </p>

                                <Button
                                    :disabled="form.processing"
                                    class="mt-6 w-full bg-shop-blue hover:bg-shop-blue-dark"
                                    size="lg"
                                    @click="handlePlaceOrder"
                                >
                                    <Loader2
                                        v-if="form.processing"
                                        class="mr-2 h-5 w-5 animate-spin"
                                    />
                                    <Lock v-else class="mr-2 h-5 w-5" />
                                    {{
                                        form.processing
                                            ? 'Processing...'
                                            : 'Place Order'
                                    }}
                                </Button>

                                <!-- Error Message -->
                                <p
                                    v-if="form.errors.checkout"
                                    class="mt-3 text-center text-sm text-shop-error"
                                >
                                    {{ form.errors.checkout }}
                                </p>
                            </div>

                            <!-- Back Link -->
                            <Link
                                :href="cartIndex().url"
                                class="inline-flex items-center gap-2 text-sm text-shop-text-light transition-colors hover:text-shop-blue"
                            >
                                <ArrowLeft class="h-4 w-4" />
                                Back to Cart
                            </Link>
                        </div>
                    </div>

                    <!-- Right Column - Order Summary -->
                    <div class="lg:col-span-2">
                        <div class="sticky top-24 rounded-xl bg-shop-cream p-6">
                            <h2
                                class="text-sm font-semibold tracking-wider text-shop-text-light uppercase"
                            >
                                Order Summary
                            </h2>

                            <div class="mt-6 space-y-4">
                                <div
                                    v-for="item in items"
                                    :key="item.id"
                                    class="flex items-center gap-3"
                                >
                                    <!-- Thumbnail with Quantity Badge -->
                                    <div
                                        class="relative h-16 w-16 shrink-0 bg-white"
                                    >
                                        <img
                                            v-if="item.product.image_url"
                                            :src="item.product.image_url"
                                            :alt="item.product.name"
                                            class="h-full w-full rounded-lg object-cover"
                                        />
                                        <div
                                            v-else
                                            class="flex h-full w-full items-center justify-center"
                                        >
                                            <span
                                                class="font-display text-lg text-gray-300"
                                            >
                                                {{
                                                    item.product.name.charAt(0)
                                                }}
                                            </span>
                                        </div>

                                        <!-- Quantity Badge -->
                                        <div
                                            class="absolute -top-2 -left-2 flex h-5 w-5 items-center justify-center rounded-full bg-shop-blue text-[10px] font-bold text-white"
                                        >
                                            {{ item.quantity }}
                                        </div>
                                    </div>

                                    <!-- Details -->
                                    <div class="min-w-0 flex-1">
                                        <h3
                                            class="truncate text-sm font-medium text-shop-text"
                                        >
                                            {{ item.product.name }}
                                        </h3>
                                    </div>

                                    <!-- Subtotal -->
                                    <span
                                        class="text-sm font-semibold text-shop-text"
                                    >
                                        ${{ item.subtotal }}
                                    </span>
                                </div>
                            </div>

                            <!-- Totals -->
                            <div
                                class="mt-6 space-y-3 border-t border-gray-200 pt-6"
                            >
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-shop-text-light"
                                        >Subtotal</span
                                    >
                                    <span class="font-semibold text-shop-text"
                                        >${{ cart.subtotal }}</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-shop-text-light"
                                        >Shipping</span
                                    >
                                    <span class="font-medium text-shop-success"
                                        >Free</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between border-t border-gray-200 pt-3"
                                >
                                    <span class="font-semibold text-shop-text"
                                        >Total</span
                                    >
                                    <span
                                        class="text-xl font-bold text-shop-text"
                                        >${{ cart.subtotal }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <EmptyState v-else type="cart" :action-url="home().url" />
        </div>
    </ShopLayout>
</template>
