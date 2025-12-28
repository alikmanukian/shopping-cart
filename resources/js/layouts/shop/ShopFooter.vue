<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';
</script>

<template>
    <footer class="border-t border-gray-100 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                <!-- Brand -->
                <Link :href="home().url" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-shop-blue">
                        <span class="font-display text-lg font-bold text-white">F</span>
                    </div>
                    <span class="font-display text-xl tracking-tight text-gray-900">
                        Fresh<span class="text-shop-blue">Cart</span>
                    </span>
                </Link>

                <!-- Copyright & Links -->
                <div class="flex flex-col items-center gap-4 md:flex-row md:gap-8">
                    <div class="flex items-center gap-6">
                        <a
                            href="#"
                            class="text-sm text-gray-400 transition-colors hover:text-gray-600"
                        >
                            Privacy Policy
                        </a>
                        <a
                            href="#"
                            class="text-sm text-gray-400 transition-colors hover:text-gray-600"
                        >
                            Terms of Service
                        </a>
                    </div>
                    <p class="text-sm text-gray-400">
                        &copy; {{ new Date().getFullYear() }} FreshCart
                    </p>
                </div>
            </div>
        </div>
    </footer>
</template>
