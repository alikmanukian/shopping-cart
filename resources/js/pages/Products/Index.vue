<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import type { Product } from '@/types/shop';
import ShopLayout from '@/layouts/shop/ShopLayout.vue';
import ProductCard from '@/components/shop/ProductCard.vue';
import ProductDetailModal from '@/components/shop/ProductDetailModal.vue';
import EmptyState from '@/components/shop/EmptyState.vue';

interface Props {
    products: Product[];
}

defineProps<Props>();

const selectedProduct = ref<Product | null>(null);
const isModalOpen = ref(false);

const openProductModal = (product: Product) => {
    selectedProduct.value = product;
    isModalOpen.value = true;
};
</script>

<template>
    <Head title="Fresh Ingredients" />

    <ShopLayout>
        <!-- Hero Section with Background Image -->
        <section class="relative h-72 overflow-hidden sm:h-80 lg:h-[420px]">
            <img
                src="/images/hero.jpg"
                alt="Fresh ingredients"
                class="absolute inset-0 h-full w-full object-cover"
            />
            <div class="relative mx-auto flex h-full max-w-7xl items-center px-4 sm:px-6 lg:px-8">
                <!-- Hero Card -->
                <div class="max-w-md rounded-2xl bg-white/95 p-6 shadow-xl backdrop-blur-sm sm:p-8">
                    <span class="inline-block rounded-full bg-shop-orange px-4 py-1.5 text-xs font-bold uppercase tracking-wide text-white">
                        Fresh Weekly
                    </span>
                    <h1 class="mt-4 font-display text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl lg:text-5xl">
                        This Week's Menu
                    </h1>
                    <p class="mt-3 text-base text-gray-600 sm:text-lg">
                        Get cooking with fresh ingredients and chef-designed recipes, delivered to your door.
                    </p>
                </div>
            </div>
        </section>

        <!-- Products Grid -->
        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <div v-if="products.length > 0">
                <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <ProductCard
                        v-for="(product, index) in products"
                        :key="product.id"
                        :product="product"
                        class="product-card"
                        :style="{ animationDelay: `${index * 60}ms` }"
                        @click="openProductModal"
                    />
                </div>
            </div>

            <EmptyState
                v-else
                type="cart"
                title="No meals available"
                description="Check back soon for fresh new recipes!"
            />
        </section>

        <!-- Product Detail Modal -->
        <ProductDetailModal
            :product="selectedProduct"
            :open="isModalOpen"
            @update:open="isModalOpen = $event"
        />
    </ShopLayout>
</template>

<style scoped>
@keyframes fade-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-card {
    animation: fade-up 0.5s ease-out forwards;
    opacity: 0;
}
</style>
