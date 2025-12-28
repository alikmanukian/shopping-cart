<script setup lang="ts">
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useCartModal } from '@/composables/useCartModal';
import { home, login, logout, register } from '@/routes';
import type { Cart } from '@/types/shop';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ChevronDown, LogOut, ShoppingCart, User } from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();
const { open: openCartModal } = useCartModal();

const user = computed(() => page.props.auth?.user);
const cart = computed(() => page.props.cart as Cart | null);
const cartCount = computed(() => cart.value?.items_count ?? 0);
const cartTotal = computed(() => cart.value?.subtotal ?? '0.00');

const handleLogout = () => {
    router.post(logout().url);
};
</script>

<template>
    <header
        class="sticky top-0 z-50 w-full border-b border-gray-100 bg-white/95 backdrop-blur-sm"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between lg:h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <Link
                        :href="home().url"
                        class="group flex items-center gap-3 transition-opacity hover:opacity-80"
                    >
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
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-6">
                    <!-- Auth Links (for guests) -->
                    <template v-if="!user">
                        <Link
                            :href="login().url"
                            class="text-sm font-medium text-gray-700 transition-colors hover:text-shop-blue"
                        >
                            Login
                        </Link>
                        <Link
                            :href="register().url"
                            class="text-sm font-medium text-gray-700 transition-colors hover:text-shop-blue"
                        >
                            Sign Up
                        </Link>
                    </template>

                    <!-- User Dropdown (for authenticated users) -->
                    <template v-if="user">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <button
                                    type="button"
                                    class="flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 hover:text-shop-blue"
                                >
                                    <User class="h-4 w-4" />
                                    <ChevronDown class="h-3.5 w-3.5" />
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-48">
                                <DropdownMenuItem
                                    @click="handleLogout"
                                    class="cursor-pointer text-red-600 focus:text-red-600"
                                >
                                    <LogOut class="mr-2 h-4 w-4" />
                                    Log out
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>

                        <!-- Cart Button -->
                        <button
                            type="button"
                            class="relative flex items-center gap-2 rounded-full bg-shop-blue px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-shop-blue-dark"
                            @click="openCartModal"
                        >
                            <ShoppingCart class="h-4 w-4" />
                            <template v-if="cartCount > 0">
                                <span>Order: ${{ cartTotal }}</span>
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-shop-orange text-xs font-bold"
                                >
                                    {{ cartCount > 9 ? '9+' : cartCount }}
                                </span>
                            </template>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </header>
</template>
