<script setup lang="ts">
import type { Product } from '@/types/shop';
import { computed } from 'vue';
import AddToCartButton from './AddToCartButton.vue';

interface Props {
    product: Product;
}

const props = defineProps<Props>();

defineEmits<{
    click: [product: Product];
}>();

const isOutOfStock = computed(() => props.product.stock_quantity <= 0);
const isLowStock = computed(
    () => props.product.stock_quantity > 0 && props.product.stock_quantity < 3,
);
</script>

<template>
    <article class="group flex flex-col overflow-hidden rounded-lg bg-white">
        <!-- Image Container -->
        <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
            <button
                type="button"
                class="h-full w-full text-left"
                @click="$emit('click', product)"
            >
                <img
                    v-if="product.image_url"
                    :src="product.image_url"
                    :alt="product.name"
                    class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                />
                <div
                    v-else
                    class="flex h-full w-full items-center justify-center bg-shop-cream"
                >
                    <span class="font-display text-5xl text-gray-300">
                        {{ product.name.charAt(0) }}
                    </span>
                </div>

                <!-- Low Stock Badge -->
                <div
                    v-if="isLowStock"
                    class="absolute top-2 left-2 rounded-full bg-shop-orange px-2.5 py-1 text-xs font-semibold text-white shadow-sm"
                >
                    Only {{ product.stock_quantity }} left
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
            </button>

            <!-- Add to Cart Button (appears on hover) -->
            <div
                v-if="!isOutOfStock"
                class="absolute right-2 bottom-2 opacity-0 transition-opacity duration-200 group-hover:opacity-100"
            >
                <AddToCartButton :product-id="product.id" size="lg" />
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-1 flex-col p-3">
            <button
                type="button"
                class="text-left"
                @click="$emit('click', product)"
            >
                <h3
                    class="text-base font-semibold text-shop-text transition-colors group-hover:text-shop-blue"
                >
                    {{ product.name }}
                </h3>
            </button>

            <p
                v-if="product.description"
                class="mt-1.5 line-clamp-2 text-sm leading-relaxed text-shop-text-light"
            >
                {{ product.description }}
            </p>

            <!-- Price -->
            <div class="mt-auto pt-4">
                <span class="text-lg font-bold text-shop-text"
                    >${{ product.price }}</span
                >
            </div>
        </div>
    </article>
</template>
