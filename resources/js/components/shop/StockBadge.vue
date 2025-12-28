<script setup lang="ts">
import { AlertCircle, CheckCircle, XCircle } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    quantity: number;
    lowStockThreshold?: number;
    showIcon?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    lowStockThreshold: 10,
    showIcon: true,
});

const status = computed(() => {
    if (props.quantity <= 0) return 'out';
    if (props.quantity <= props.lowStockThreshold) return 'low';
    return 'in';
});

const label = computed(() => {
    if (status.value === 'out') return 'Out of stock';
    if (status.value === 'low') return `Only ${props.quantity} left!`;
    return 'In stock';
});
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium',
            {
                'bg-red-50 text-red-700': status === 'out',
                'bg-amber-50 text-amber-700': status === 'low',
                'bg-emerald-50 text-emerald-700': status === 'in',
            },
        ]"
    >
        <template v-if="showIcon">
            <XCircle v-if="status === 'out'" class="h-3.5 w-3.5" />
            <AlertCircle v-else-if="status === 'low'" class="h-3.5 w-3.5" />
            <CheckCircle v-else class="h-3.5 w-3.5" />
        </template>
        {{ label }}
    </span>
</template>
