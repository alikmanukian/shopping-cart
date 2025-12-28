<script setup lang="ts">
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-shop-cream p-6 md:p-10"
    >
        <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link :href="home()" class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-shop-blue"
                        >
                            <span
                                class="font-display text-lg font-bold text-white"
                                >F</span
                            >
                        </div>
                        <span
                            class="font-display text-xl tracking-tight text-gray-900"
                        >
                            Fresh<span class="text-shop-blue">Cart</span>
                        </span>
                    </Link>
                    <div class="mt-2 space-y-2 text-center">
                        <h1 class="text-2xl font-semibold text-gray-900">
                            {{ title }}
                        </h1>
                        <p class="text-center text-sm text-gray-500">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
