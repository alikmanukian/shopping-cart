<script setup lang="ts">
import { Toaster as Sonner, type ToasterProps } from 'vue-sonner'

const props = defineProps<ToasterProps>()
</script>

<template>
    <Sonner
        class="toaster group"
        :toast-options="{
            classes: {
                toast: 'group toast group-[.toaster]:bg-white group-[.toaster]:text-gray-900 group-[.toaster]:border-gray-200 group-[.toaster]:shadow-lg',
                description: 'group-[.toast]:text-gray-500',
                actionButton: 'group-[.toast]:bg-shop-blue group-[.toast]:text-white',
                cancelButton: 'group-[.toast]:bg-gray-100 group-[.toast]:text-gray-500',
            },
        }"
        v-bind="props"
    />
</template>
