<script setup lang="ts">
interface Props {
    price: string;
    size?: 'sm' | 'md' | 'lg' | 'xl';
    strikethrough?: boolean;
}

withDefaults(defineProps<Props>(), {
    size: 'md',
    strikethrough: false,
});
</script>

<template>
    <span
        :class="[
            'font-semibold tracking-tight tabular-nums',
            {
                'text-sm': size === 'sm',
                'text-base': size === 'md',
                'text-lg': size === 'lg',
                'text-2xl': size === 'xl',
                'text-gray-400 line-through': strikethrough,
                'text-gray-900': !strikethrough,
            },
        ]"
    >
        ${{ price }}
    </span>
</template>
