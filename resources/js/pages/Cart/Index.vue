<script setup lang="ts">
import CartItem from '@/components/shop/CartItem.vue';
import CartSummary from '@/components/shop/CartSummary.vue';
import EmptyState from '@/components/shop/EmptyState.vue';
import ShopLayout from '@/layouts/shop/ShopLayout.vue';
import { home } from '@/routes';
import type { Cart, CartItem as CartItemType } from '@/types/shop';
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

interface Props {
    cart: Cart;
    items: CartItemType[];
}

defineProps<Props>();
</script>

<template>
    <Head title="Review Your Order" />

    <ShopLayout>
        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
            <!-- Header -->
            <div class="mb-8">
                <h1
                    class="font-display text-2xl font-bold text-shop-text md:text-3xl"
                >
                    Review your order
                </h1>
            </div>

            <!-- Cart Content -->
            <div v-if="items.length > 0" class="grid gap-8 lg:grid-cols-5">
                <!-- Cart Items -->
                <div class="lg:col-span-3">
                    <div
                        class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100"
                    >
                        <CartItem
                            v-for="item in items"
                            :key="item.id"
                            :item="item"
                        />

                        <!-- Add More Items Link -->
                        <Link
                            :href="home().url"
                            class="mt-4 flex items-center gap-2 text-sm font-medium text-shop-blue transition-colors hover:text-shop-blue-dark"
                        >
                            <Plus class="h-4 w-4" />
                            Add Items
                        </Link>
                    </div>
                </div>

                <!-- Summary Sidebar -->
                <div class="lg:col-span-2">
                    <div class="sticky top-24">
                        <CartSummary :cart="cart" />
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <EmptyState v-else type="cart" :action-url="home().url" />
        </div>
    </ShopLayout>
</template>
