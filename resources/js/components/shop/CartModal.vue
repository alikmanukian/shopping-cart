<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { Sheet, SheetContent } from '@/components/ui/sheet';
import { toast } from '@/components/ui/sonner';
import { useCartModal } from '@/composables/useCartModal';
import { home } from '@/routes';
import { destroy as cartDestroy, update as cartUpdate } from '@/routes/cart';
import { index as checkoutIndex } from '@/routes/checkout';
import type { Cart, CartItem } from '@/types/shop';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Minus, Plus, ShoppingBag, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();
const { isOpen, close } = useCartModal();

const cart = computed(() => page.props.cart as Cart | null);
const items = computed(() => cart.value?.items ?? []);
const isEmpty = computed(() => items.value.length === 0);

const handleOpenChange = (value: boolean) => {
    if (!value) {
        close();
    }
};

// Item quantity update
const updateQuantity = (item: CartItem, newQuantity: number) => {
    if (newQuantity < 1 || newQuantity > item.product.stock_quantity) return;

    const form = useForm({ quantity: newQuantity });
    form.patch(cartUpdate(item).url, {
        preserveScroll: true,
        onError: (errors) => {
            const errorMessage = Object.values(errors)[0] as string;
            toast.error('Unable to update quantity', {
                description: errorMessage,
            });
        },
    });
};

// Item delete
const deleteItem = (item: CartItem) => {
    const form = useForm({});
    form.delete(cartDestroy(item).url, {
        preserveScroll: true,
        onError: (errors) => {
            const errorMessage = Object.values(errors)[0] as string;
            toast.error('Unable to remove item', {
                description: errorMessage,
            });
        },
    });
};
</script>

<template>
    <Sheet :open="isOpen" @update:open="handleOpenChange">
        <SheetContent
            side="right"
            class="cart-modal flex w-full flex-col p-0 sm:max-w-md"
        >
            <!-- Header -->
            <DialogTitle
                class="flex items-center justify-between border-b border-gray-100 px-6 py-4"
            >
                <h2 class="text-xl font-semibold text-gray-900">
                    Review your order
                </h2>
            </DialogTitle>

            <!-- Cart Items -->
            <DialogDescription class="flex-1 overflow-y-auto">
                <!-- Empty State -->
                <div
                    v-if="isEmpty"
                    class="flex h-full flex-col items-center justify-center px-6 py-12"
                >
                    <div
                        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100"
                    >
                        <ShoppingBag class="h-8 w-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">
                        Your cart is empty
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Add some items to get started
                    </p>
                    <Link :href="home().url" @click="close">
                        <Button
                            class="mt-6 rounded-full bg-shop-blue hover:bg-shop-blue-dark"
                        >
                            Browse meals
                        </Button>
                    </Link>
                </div>

                <!-- Items List -->
                <div v-else class="divide-y divide-gray-100 px-6">
                    <div
                        v-for="item in items"
                        :key="item.id"
                        class="flex gap-4 py-4"
                    >
                        <!-- Product Image with Quantity Badge -->
                        <div class="relative h-20 w-20 shrink-0 bg-gray-100">
                            <img
                                v-if="item.product.image_url"
                                :src="item.product.image_url"
                                :alt="item.product.name"
                                class="h-full w-full overflow-hidden rounded-lg object-cover"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-shop-cream"
                            >
                                <span
                                    class="font-display text-xl text-gray-300"
                                >
                                    {{ item.product.name.charAt(0) }}
                                </span>
                            </div>

                            <!-- Quantity Badge -->
                            <div
                                class="absolute -top-2 -left-2 flex h-6 w-6 items-center justify-center rounded-full bg-shop-blue text-xs font-bold text-white shadow-sm"
                            >
                                {{ item.quantity }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex flex-1 flex-col">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        {{ item.product.name }}
                                    </h3>
                                    <p class="mt-0.5 text-xs text-gray-500">
                                        ${{ item.product.price }} each
                                    </p>
                                </div>
                                <span class="text-sm font-bold text-gray-900">
                                    ${{ item.subtotal }}
                                </span>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="mt-2 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        :disabled="item.quantity <= 1"
                                        class="flex h-7 w-7 items-center justify-center rounded-full border border-gray-200 text-gray-600 transition-colors hover:border-shop-blue hover:text-shop-blue disabled:cursor-not-allowed disabled:opacity-40"
                                        @click="
                                            updateQuantity(
                                                item,
                                                item.quantity - 1,
                                            )
                                        "
                                    >
                                        <Minus class="h-3 w-3" />
                                    </button>
                                    <span
                                        class="w-6 text-center text-sm font-medium text-gray-900"
                                    >
                                        {{ item.quantity }}
                                    </span>
                                    <button
                                        type="button"
                                        :disabled="
                                            item.quantity >=
                                            item.product.stock_quantity
                                        "
                                        class="flex h-7 w-7 items-center justify-center rounded-full border border-gray-200 text-gray-600 transition-colors hover:border-shop-blue hover:text-shop-blue disabled:cursor-not-allowed disabled:opacity-40"
                                        @click="
                                            updateQuantity(
                                                item,
                                                item.quantity + 1,
                                            )
                                        "
                                    >
                                        <Plus class="h-3 w-3" />
                                    </button>
                                </div>

                                <button
                                    type="button"
                                    class="flex items-center gap-1 text-xs text-gray-400 transition-colors hover:text-red-500"
                                    @click="deleteItem(item)"
                                >
                                    <Trash2 class="h-3.5 w-3.5" />
                                    <span>Remove</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </DialogDescription>

            <!-- Footer with Summary -->
            <div
                v-if="!isEmpty && cart"
                class="border-t border-gray-100 bg-shop-cream p-6"
            >
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium text-gray-900"
                            >${{ cart.subtotal }}</span
                        >
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Shipping</span>
                        <span class="font-medium text-shop-success">Free</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-semibold text-gray-900"
                                >Total</span
                            >
                            <span class="text-lg font-bold text-gray-900"
                                >${{ cart.subtotal }}</span
                            >
                        </div>
                    </div>
                </div>

                <Link
                    :href="checkoutIndex().url"
                    class="mt-4 block"
                    @click="close"
                >
                    <Button
                        class="w-full rounded-full bg-shop-blue hover:bg-shop-blue-dark"
                        size="lg"
                    >
                        Continue to checkout
                    </Button>
                </Link>

                <Link
                    :href="home().url"
                    class="mt-3 flex items-center justify-center gap-1 text-sm font-medium text-shop-blue transition-colors hover:text-shop-blue-dark"
                    @click="close"
                >
                    <Plus class="h-4 w-4" />
                    Add more items
                </Link>
            </div>
        </SheetContent>
    </Sheet>
</template>

<style scoped>
/* Hide the default close button from SheetContent */
.cart-modal
    :deep(
        [data-slot='sheet-content'] > button:last-of-type:not(.custom-close)
    ) {
    display: none;
}

/* Alternative: Hide the DialogClose component */
.cart-modal :deep(.absolute.top-4.right-4) {
    display: none;
}
</style>
