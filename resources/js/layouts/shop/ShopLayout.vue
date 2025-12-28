<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import ShopHeader from './ShopHeader.vue';
import ShopFooter from './ShopFooter.vue';
import LoginModal from '@/components/shop/LoginModal.vue';
import CartModal from '@/components/shop/CartModal.vue';
import { Sonner } from '@/components/ui/sonner';
import { useLoginModal } from '@/composables/useLoginModal';
import { CheckCircle, XCircle, X } from 'lucide-vue-next';

const page = usePage();
const { isOpen: loginModalOpen, executePendingAction } = useLoginModal();

const handleLoginSuccess = () => {
    executePendingAction();
};

const flash = computed(() => page.props.flash as { success?: string; error?: string } | undefined);
const showFlash = ref(false);
const flashTimeout = ref<ReturnType<typeof setTimeout> | null>(null);

const dismissFlash = () => {
    showFlash.value = false;
};

onMounted(() => {
    if (flash.value?.success || flash.value?.error) {
        showFlash.value = true;
        flashTimeout.value = setTimeout(() => {
            showFlash.value = false;
        }, 5000);
    }
});

onUnmounted(() => {
    if (flashTimeout.value) {
        clearTimeout(flashTimeout.value);
    }
});
</script>

<template>
    <div class="flex min-h-screen flex-col bg-shop-cream">
        <ShopHeader />
        <Sonner position="top-center" />

        <!-- Flash Messages -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="translate-y-[-100%] opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-[-100%] opacity-0"
        >
            <div
                v-if="showFlash && (flash?.success || flash?.error)"
                class="fixed left-1/2 top-20 z-50 w-full max-w-md -translate-x-1/2 px-4"
            >
                <div
                    :class="[
                        'flex items-center gap-3 rounded-lg px-4 py-3 shadow-lg',
                        flash?.success
                            ? 'bg-shop-success text-white'
                            : 'bg-shop-error text-white',
                    ]"
                >
                    <CheckCircle v-if="flash?.success" class="h-5 w-5 shrink-0" />
                    <XCircle v-else class="h-5 w-5 shrink-0" />
                    <p class="flex-1 text-sm font-medium">
                        {{ flash?.success || flash?.error }}
                    </p>
                    <button
                        type="button"
                        class="rounded-full p-1 transition-colors hover:bg-white/20"
                        @click="dismissFlash"
                    >
                        <X class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </Transition>

        <main class="flex-1">
            <slot />
        </main>

        <ShopFooter />

        <!-- Login Modal -->
        <LoginModal
            v-model:open="loginModalOpen"
            @success="handleLoginSuccess"
        />

        <!-- Cart Modal -->
        <CartModal />
    </div>
</template>
