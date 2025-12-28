<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import Button from '@/components/ui/button/Button.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { store } from '@/routes/login';
import { register } from '@/routes';

interface Props {
    open: boolean;
}

defineProps<Props>();

const emit = defineEmits<{
    'update:open': [value: boolean];
    'success': [];
}>();

const email = ref('');
const password = ref('');
const remember = ref(false);
const processing = ref(false);
const errors = ref<{ email?: string; password?: string }>({});

const handleSubmit = () => {
    processing.value = true;
    errors.value = {};

    router.post(store().url, {
        email: email.value,
        password: password.value,
        remember: remember.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            processing.value = false;
            email.value = '';
            password.value = '';
            emit('update:open', false);
            emit('success');
        },
        onError: (errs) => {
            processing.value = false;
            errors.value = errs as { email?: string; password?: string };
        },
    });
};

const handleOpenChange = (value: boolean) => {
    emit('update:open', value);
    if (!value) {
        errors.value = {};
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="handleOpenChange">
        <DialogContent class="sm:max-w-md">
            <DialogHeader class="text-center">
                <DialogTitle class="text-2xl font-semibold">
                    Welcome back!
                </DialogTitle>
                <DialogDescription>
                    Don't have an account?
                    <TextLink :href="register()" class="text-shop-blue hover:text-shop-blue-dark">
                        Get started
                    </TextLink>
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="handleSubmit" class="mt-6 space-y-4">
                <div class="space-y-2">
                    <Label for="modal-email">Email</Label>
                    <Input
                        id="modal-email"
                        v-model="email"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="Enter email address"
                        class="rounded-lg"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="space-y-2">
                    <Label for="modal-password">Password</Label>
                    <Input
                        id="modal-password"
                        v-model="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter password"
                        class="rounded-lg"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center space-x-2">
                    <Checkbox
                        id="modal-remember"
                        :checked="remember"
                        @update:checked="remember = $event"
                    />
                    <Label for="modal-remember" class="text-sm font-normal">
                        Remember me
                    </Label>
                </div>

                <Button
                    type="submit"
                    :disabled="processing"
                    class="w-full rounded-full bg-shop-blue hover:bg-shop-blue-dark"
                >
                    <Loader2 v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                    {{ processing ? 'Logging in...' : 'Login' }}
                </Button>
            </form>
        </DialogContent>
    </Dialog>
</template>
