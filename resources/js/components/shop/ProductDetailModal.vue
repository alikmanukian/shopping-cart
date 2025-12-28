<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import { X, Loader2, Check, Plus, Minus } from 'lucide-vue-next';
import type { Product } from '@/types/shop';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
} from '@/components/ui/dialog';
import Button from '@/components/ui/button/Button.vue';
import { toast } from '@/components/ui/sonner';
import { store as cartStore } from '@/routes/cart';
import { useLoginModal } from '@/composables/useLoginModal';

interface Props {
    product: Product | null;
    open: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    'update:open': [value: boolean];
}>();

const page = usePage();
const { openWithCallback } = useLoginModal();
const isAuthenticated = computed(() => !!page.props.auth?.user);
const quantity = ref(1);
const showSuccess = ref(false);

const isOutOfStock = computed(
    () => !props.product || props.product.stock_quantity <= 0,
);
const isLowStock = computed(
    () =>
        props.product &&
        props.product.stock_quantity > 0 &&
        props.product.stock_quantity < 3,
);

const form = useForm({
    product_id: 0,
    quantity: 1,
});

// Reset quantity when product changes
watch(
    () => props.product,
    () => {
        quantity.value = 1;
        showSuccess.value = false;
    },
);

const incrementQuantity = () => {
    if (props.product && quantity.value < props.product.stock_quantity) {
        quantity.value++;
    }
};

const decrementQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const addToCart = () => {
    if (!props.product) return;

    const addingAllStock = quantity.value >= props.product.stock_quantity;

    form.product_id = props.product.id;
    form.quantity = quantity.value;

    form.post(cartStore().url, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess.value = true;

            if (addingAllStock) {
                toast.success('All available items added to cart!', {
                    description: `You've added all ${quantity.value} ${props.product?.name} to your cart.`,
                });
            }

            setTimeout(() => {
                showSuccess.value = false;
                emit('update:open', false);
            }, 1500);
        },
        onError: (errors) => {
            const errorMessage = Object.values(errors)[0] as string;
            toast.error('Unable to add to cart', {
                description: errorMessage,
            });
        },
    });
};

const handleAddToCart = () => {
    if (!isAuthenticated.value) {
        openWithCallback(addToCart);
        return;
    }

    addToCart();
};

const totalPrice = computed(() => {
    if (!props.product) return '0.00';
    return (parseFloat(props.product.price) * quantity.value).toFixed(2);
});

const handleOpenChange = (value: boolean) => {
    emit('update:open', value);
};
</script>

<template>
    <Dialog :open="open" @update:open="handleOpenChange">
        <DialogContent
            class="max-h-[90vh] overflow-y-auto rounded-none border-0 p-0 sm:max-w-md"
        >
            <!-- Close Button -->
            <button
                type="button"
                class="absolute top-4 right-4 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-md transition-colors hover:bg-gray-50"
                @click="handleOpenChange(false)"
            >
                <X class="h-4 w-4 text-gray-500" />
            </button>

            <div v-if="product">
                <!-- Product Image -->
                <div
                    class="relative aspect-[4/3] w-full overflow-hidden bg-gray-100"
                >
                    <img
                        v-if="product.image_url"
                        :src="product.image_url"
                        :alt="product.name"
                        class="h-full w-full object-cover"
                    />
                    <div
                        v-else
                        class="flex h-full w-full items-center justify-center bg-shop-cream"
                    >
                        <span class="font-display text-6xl text-gray-300">
                            {{ product.name.charAt(0) }}
                        </span>
                    </div>

                    <!-- Out of Stock Overlay -->
                    <div
                        v-if="isOutOfStock"
                        class="absolute inset-0 flex items-center justify-center bg-black/50"
                    >
                        <span
                            class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-gray-900"
                        >
                            Out of Stock
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Title -->
                    <DialogTitle class="text-2xl font-bold text-gray-900">
                        {{ product.name }}
                    </DialogTitle>

                    <!-- Price -->
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-shop-blue"
                            >${{ product.price }}</span
                        >
                    </div>

                    <!-- Stock Info -->
                    <div class="mt-3 flex items-center gap-2">
                        <span
                            v-if="isOutOfStock"
                            class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700"
                        >
                            Out of stock
                        </span>
                        <span
                            v-else-if="isLowStock"
                            class="inline-flex items-center rounded-full bg-shop-orange/10 px-2.5 py-0.5 text-xs font-medium text-shop-orange"
                        >
                            Only {{ product.stock_quantity }} left!
                        </span>
                        <span v-else class="text-sm text-gray-500">
                            {{ product.stock_quantity }} in stock
                        </span>
                    </div>

                    <!-- Details Section -->
                    <DialogDescription
                        v-if="product.description"
                        class="mt-4 border-t border-gray-100 pt-4"
                    >
                        <h3
                            class="text-xs font-semibold tracking-wider text-gray-500 uppercase"
                        >
                            Details
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            {{ product.description }}
                        </p>
                    </DialogDescription>

                    <!-- Add to Cart Section -->
                    <div class="mt-6 flex items-center gap-4">
                        <!-- Quantity Selector -->
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                :disabled="quantity <= 1 || isOutOfStock"
                                class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 text-gray-600 transition-colors hover:border-shop-blue hover:text-shop-blue disabled:cursor-not-allowed disabled:opacity-40"
                                @click="decrementQuantity"
                            >
                                <Minus class="h-3.5 w-3.5" />
                            </button>
                            <span
                                class="w-6 text-center text-base font-semibold text-gray-900"
                            >
                                {{ quantity }}
                            </span>
                            <button
                                type="button"
                                :disabled="
                                    !product ||
                                    quantity >= product.stock_quantity ||
                                    isOutOfStock
                                "
                                class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 text-gray-600 transition-colors hover:border-shop-blue hover:text-shop-blue disabled:cursor-not-allowed disabled:opacity-40"
                                @click="incrementQuantity"
                            >
                                <Plus class="h-3.5 w-3.5" />
                            </button>
                        </div>

                        <!-- Add Button -->
                        <Button
                            :disabled="isOutOfStock || form.processing"
                            :class="[
                                'flex-1 rounded-full',
                                'bg-shop-blue hover:bg-shop-blue-dark',
                                {
                                    'bg-shop-success hover:bg-shop-success':
                                        showSuccess,
                                },
                            ]"
                            @click="handleAddToCart"
                        >
                            <Transition
                                enter-active-class="transition-all duration-200"
                                enter-from-class="opacity-0 scale-75"
                                enter-to-class="opacity-100 scale-100"
                                leave-active-class="transition-all duration-200"
                                leave-from-class="opacity-100 scale-100"
                                leave-to-class="opacity-0 scale-75"
                                mode="out-in"
                            >
                                <span
                                    v-if="form.processing"
                                    class="flex items-center gap-2"
                                >
                                    <Loader2 class="h-4 w-4 animate-spin" />
                                    Adding...
                                </span>
                                <span
                                    v-else-if="showSuccess"
                                    class="flex items-center gap-2"
                                >
                                    <Check class="h-4 w-4" />
                                    Added!
                                </span>
                                <span v-else> Add — ${{ totalPrice }} </span>
                            </Transition>
                        </Button>
                    </div>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
