<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Package, ShoppingBag } from 'lucide-vue-next';

interface Props {
    type: 'cart' | 'orders';
    title?: string;
    description?: string;
    actionLabel?: string;
    actionUrl?: string;
}

const props = withDefaults(defineProps<Props>(), {
    title: '',
    description: '',
    actionLabel: '',
    actionUrl: '',
});

const defaultContent = {
    cart: {
        title: 'Your cart is empty',
        description:
            "Looks like you haven't added anything to your cart yet. Browse our menu to find something delicious!",
        actionLabel: 'Browse Menu',
        icon: ShoppingBag,
    },
    orders: {
        title: 'No orders yet',
        description:
            "You haven't placed any orders yet. Start shopping to see your order history here.",
        actionLabel: 'Start Shopping',
        icon: Package,
    },
};

const content = defaultContent[props.type];
</script>

<template>
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div
            class="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100"
        >
            <component :is="content.icon" class="h-10 w-10 text-gray-400" />
        </div>

        <h3 class="font-display text-xl font-medium text-gray-900">
            {{ title || content.title }}
        </h3>

        <p class="mt-2 max-w-sm text-sm text-gray-500">
            {{ description || content.description }}
        </p>

        <div v-if="actionUrl" class="mt-6">
            <Link :href="actionUrl">
                <Button class="bg-shop-blue hover:bg-shop-blue-dark">
                    {{ actionLabel || content.actionLabel }}
                    <ArrowRight class="ml-2 h-4 w-4" />
                </Button>
            </Link>
        </div>
    </div>
</template>
