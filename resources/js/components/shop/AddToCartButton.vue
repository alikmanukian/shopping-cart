<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { ShoppingCart, Loader2, Check } from 'lucide-vue-next';
import Button from '@/components/ui/button/Button.vue';
import { toast } from '@/components/ui/sonner';
import { store as cartStore } from '@/routes/cart';
import { useLoginModal } from '@/composables/useLoginModal';

interface Props {
    productId: number;
    quantity?: number;
    disabled?: boolean;
    size?: 'default' | 'sm' | 'lg' | 'icon' | 'icon-sm' | 'icon-lg';
    fullWidth?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    quantity: 1,
    disabled: false,
    size: 'default',
    fullWidth: false,
});

const page = usePage();
const { openWithCallback } = useLoginModal();
const isAuthenticated = computed(() => !!page.props.auth?.user);
const showSuccess = ref(false);

const form = useForm({
    product_id: props.productId,
    quantity: props.quantity,
});

const addToCart = () => {
    form.product_id = props.productId;
    form.quantity = props.quantity;

    form.post(cartStore().url, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess.value = true;
            setTimeout(() => {
                showSuccess.value = false;
            }, 2000);
        },
        onError: (errors) => {
            const errorMessage = Object.values(errors)[0] as string;
            toast.error('Unable to add to cart', {
                description: errorMessage,
            });
        },
    });
};

const handleSubmit = () => {
    if (!isAuthenticated.value) {
        openWithCallback(addToCart);
        return;
    }

    addToCart();
};
</script>

<template>
    <Button
        type="button"
        :disabled="disabled || form.processing"
        :class="[
            'relative overflow-hidden rounded-full transition-all duration-200',
            'bg-white text-shop-blue hover:bg-shop-blue hover:text-white',
            { 'w-full': fullWidth },
            { 'bg-shop-success hover:bg-shop-success': showSuccess },
        ]"
        :size="size"
        @click="handleSubmit"
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
            <span v-if="form.processing" class="flex items-center gap-2">
                <Loader2 class="h-4 w-4 animate-spin" />
                <span>Adding...</span>
            </span>
            <span v-else-if="showSuccess" class="flex items-center gap-2">
                <Check class="h-4 w-4" />
                <span>Added!</span>
            </span>
            <span v-else class="flex items-center gap-2">
                <ShoppingCart class="h-4 w-4" />
                <span>Add to Cart</span>
            </span>
        </Transition>
    </Button>
</template>
