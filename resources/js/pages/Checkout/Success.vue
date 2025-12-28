<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle, Package, ArrowRight } from 'lucide-vue-next';
import type { Order, OrderItem } from '@/types/shop';
import ShopLayout from '@/layouts/shop/ShopLayout.vue';
import PriceDisplay from '@/components/shop/PriceDisplay.vue';
import Button from '@/components/ui/button/Button.vue';
import { home } from '@/routes';

interface Props {
    order: Order & { items: OrderItem[] };
}

defineProps<Props>();
</script>

<template>
    <Head title="Order Confirmed" />

    <ShopLayout>
        <div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8 lg:py-20">
            <!-- Success Header -->
            <div class="text-center">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-shop-success/10">
                    <CheckCircle class="h-12 w-12 text-shop-success" />
                </div>

                <h1 class="font-display text-3xl font-bold text-gray-900">
                    Order Confirmed!
                </h1>

                <p class="mt-3 text-gray-500">
                    Thank you for your order. We've received your request and will begin processing it shortly.
                </p>
            </div>

            <!-- Order Details Card -->
            <div class="mt-10 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <!-- Order Number -->
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <p class="text-sm text-gray-500">Order Number</p>
                        <p class="mt-1 font-display text-lg font-semibold text-gray-900">
                            {{ order.order_number }}
                        </p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-shop-blue/10">
                        <Package class="h-5 w-5 text-shop-blue" />
                    </div>
                </div>

                <!-- Order Items -->
                <div class="divide-y divide-gray-100">
                    <div
                        v-for="item in order.items"
                        :key="item.id"
                        class="flex items-center gap-4 py-4"
                    >
                        <!-- Thumbnail -->
                        <div class="h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-gray-100">
                            <img
                                v-if="item.product?.image_url"
                                :src="item.product.image_url"
                                :alt="item.product_name"
                                class="h-full w-full object-cover"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-shop-cream to-gray-100"
                            >
                                <span class="font-display text-lg text-gray-300">
                                    {{ item.product_name.charAt(0) }}
                                </span>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">
                                {{ item.product_name }}
                            </h3>
                            <p class="mt-0.5 text-sm text-gray-500">
                                Qty: {{ item.quantity }} &times; ${{ item.product_price }}
                            </p>
                        </div>

                        <!-- Subtotal -->
                        <PriceDisplay :price="item.subtotal" size="sm" />
                    </div>
                </div>

                <!-- Order Total -->
                <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                    <span class="text-base font-medium text-gray-900">
                        Total
                    </span>
                    <PriceDisplay :price="order.total" size="lg" />
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-center">
                <Link :href="home().url">
                    <Button
                        size="lg"
                        class="rounded-full bg-shop-blue hover:bg-shop-blue-dark"
                    >
                        Continue Shopping
                        <ArrowRight class="ml-2 h-4 w-4" />
                    </Button>
                </Link>
            </div>

            <!-- Additional Info -->
            <p class="mt-8 text-center text-sm text-gray-500">
                A confirmation email has been sent to your email address.
            </p>
        </div>
    </ShopLayout>
</template>
