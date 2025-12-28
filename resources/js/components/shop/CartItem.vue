<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Trash2, Loader2 } from 'lucide-vue-next';
import type { CartItem } from '@/types/shop';
import { update as cartUpdate, destroy as cartDestroy } from '@/routes/cart';
import QuantitySelector from './QuantitySelector.vue';

interface Props {
    item: CartItem;
}

const props = defineProps<Props>();

const quantity = ref(props.item.quantity);
const isUpdating = ref(false);
const isDeleting = ref(false);

const updateForm = useForm({
    quantity: props.item.quantity,
});

const deleteForm = useForm({});

let debounceTimeout: ReturnType<typeof setTimeout> | null = null;

watch(quantity, (newQuantity) => {
    if (newQuantity === props.item.quantity) return;

    if (debounceTimeout) clearTimeout(debounceTimeout);

    debounceTimeout = setTimeout(() => {
        isUpdating.value = true;
        updateForm.quantity = newQuantity;
        updateForm.patch(cartUpdate(props.item).url, {
            preserveScroll: true,
            onFinish: () => {
                isUpdating.value = false;
            },
        });
    }, 500);
});

const handleDelete = () => {
    isDeleting.value = true;
    deleteForm.delete(cartDestroy(props.item).url, {
        preserveScroll: true,
        onFinish: () => {
            isDeleting.value = false;
        },
    });
};
</script>

<template>
    <div
        :class="[
            'group flex items-start gap-4 border-b border-gray-100 py-5 last:border-b-0',
            { 'opacity-50': isDeleting },
        ]"
    >
        <!-- Product Image with Quantity Badge -->
        <div
            class="relative h-20 w-20 shrink-0 bg-gray-100"
        >
            <img
                v-if="item.product.image_url"
                :src="item.product.image_url"
                :alt="item.product.name"
                class="h-full w-full object-cover rounded-lg"
            />
            <div
                v-else
                class="flex h-full w-full items-center justify-center bg-shop-cream"
            >
                <span class="font-display text-xl text-gray-300">
                    {{ item.product.name.charAt(0) }}
                </span>
            </div>

            <!-- Quantity Badge -->
            <div
                class="absolute -left-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-shop-blue text-xs font-bold text-white shadow-sm"
            >
                {{ item.quantity }}
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-1 flex-col">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                    <p
                        class="text-base font-semibold text-shop-text transition-colors"
                    >
                        {{ item.product.name }}
                    </p>
                    <p class="mt-0.5 text-sm text-shop-text-light">
                        ${{ item.product.price }} each
                    </p>
                </div>

                <!-- Subtotal -->
                <span class="text-base font-bold text-shop-text">
                    ${{ item.subtotal }}
                </span>
            </div>

            <!-- Actions Row -->
            <div class="mt-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <QuantitySelector
                        v-model="quantity"
                        :max="item.product.stock_quantity"
                        :disabled="isUpdating || isDeleting"
                        size="sm"
                    />
                    <Loader2
                        v-if="isUpdating"
                        class="h-4 w-4 animate-spin text-shop-text-light"
                    />
                </div>

                <button
                    type="button"
                    :disabled="isDeleting"
                    class="flex items-center gap-1.5 text-sm text-shop-text-light transition-colors hover:text-shop-error disabled:opacity-50"
                    @click="handleDelete"
                >
                    <Loader2 v-if="isDeleting" class="h-4 w-4 animate-spin" />
                    <Trash2 v-else class="h-4 w-4" />
                    <span>Remove</span>
                </button>
            </div>
        </div>
    </div>
</template>
