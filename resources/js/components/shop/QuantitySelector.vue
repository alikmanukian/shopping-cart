<script setup lang="ts">
import { computed } from 'vue';
import { Minus, Plus } from 'lucide-vue-next';

interface Props {
    modelValue: number;
    min?: number;
    max?: number;
    disabled?: boolean;
    size?: 'sm' | 'md';
}

const props = withDefaults(defineProps<Props>(), {
    min: 1,
    max: 99,
    disabled: false,
    size: 'md',
});

const emit = defineEmits<{
    'update:modelValue': [value: number];
}>();

const canDecrement = computed(() => props.modelValue > props.min && !props.disabled);
const canIncrement = computed(() => props.modelValue < props.max && !props.disabled);

const decrement = () => {
    if (canDecrement.value) {
        emit('update:modelValue', props.modelValue - 1);
    }
};

const increment = () => {
    if (canIncrement.value) {
        emit('update:modelValue', props.modelValue + 1);
    }
};

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    let value = parseInt(target.value, 10);

    if (isNaN(value)) value = props.min;
    if (value < props.min) value = props.min;
    if (value > props.max) value = props.max;

    emit('update:modelValue', value);
};
</script>

<template>
    <div
        :class="[
            'inline-flex items-center rounded-lg border border-gray-200 bg-white',
            { 'opacity-50': disabled },
        ]"
    >
        <button
            type="button"
            :disabled="!canDecrement"
            :class="[
                'flex items-center justify-center rounded-l-lg transition-colors',
                'hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40',
                size === 'sm' ? 'h-8 w-8' : 'h-10 w-10',
            ]"
            @click="decrement"
        >
            <Minus :class="size === 'sm' ? 'h-3.5 w-3.5' : 'h-4 w-4'" />
        </button>

        <input
            type="number"
            :value="modelValue"
            :min="min"
            :max="max"
            :disabled="disabled"
            :class="[
                'border-x border-gray-200 bg-transparent text-center font-medium tabular-nums',
                'focus:outline-none focus:ring-0',
                '[appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none',
                size === 'sm' ? 'h-8 w-10 text-sm' : 'h-10 w-14 text-base',
            ]"
            @input="handleInput"
        />

        <button
            type="button"
            :disabled="!canIncrement"
            :class="[
                'flex items-center justify-center rounded-r-lg transition-colors',
                'hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40',
                size === 'sm' ? 'h-8 w-8' : 'h-10 w-10',
            ]"
            @click="increment"
        >
            <Plus :class="size === 'sm' ? 'h-3.5 w-3.5' : 'h-4 w-4'" />
        </button>
    </div>
</template>
