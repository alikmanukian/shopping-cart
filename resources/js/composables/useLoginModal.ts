import { ref } from 'vue';

const isOpen = ref(false);
const pendingAction = ref<(() => void) | null>(null);

export function useLoginModal() {
    const open = () => {
        isOpen.value = true;
    };

    const close = () => {
        isOpen.value = false;
        pendingAction.value = null;
    };

    const openWithCallback = (callback: () => void) => {
        pendingAction.value = callback;
        isOpen.value = true;
    };

    const executePendingAction = () => {
        if (pendingAction.value) {
            pendingAction.value();
            pendingAction.value = null;
        }
    };

    return {
        isOpen,
        open,
        close,
        openWithCallback,
        executePendingAction,
    };
}
