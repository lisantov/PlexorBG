<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import SessionController from '@/actions/App/Http/Controllers/Auth/SessionController';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { register } from '@/routes';

defineOptions({
    layout: {
        title: 'Вход',
        description: 'Email или логин и пароль',
    },
});
</script>

<template>
    <Head title="Вход" />

    <Form
        v-bind="SessionController.store.form()"
        :reset-on-success="['password']"
        class="flex flex-col gap-6"
        v-slot="{ errors, processing }"
    >
        <div class="grid gap-2">
            <Label for="identifier">Email or login</Label>
            <Input
                id="identifier"
                type="text"
                name="identifier"
                required
                autofocus
                autocomplete="username"
                placeholder="Email or login"
            />
            <InputError :message="errors.identifier" />
        </div>

        <div class="grid gap-2">
            <Label for="password">Password</Label>
            <PasswordInput
                id="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Password"
            />
            <InputError :message="errors.password" />
        </div>

        <div class="flex items-center gap-2">
            <input
                id="remember"
                type="checkbox"
                name="remember"
                value="1"
                class="size-4 rounded border-input"
            />
            <Label for="remember" class="font-normal">Remember me</Label>
        </div>

        <Button type="submit" class="w-full" :disabled="processing">
            Log in
        </Button>

        <p class="text-center text-sm text-muted-foreground">
            Don't have an account?
            <TextLink :href="register()">Sign up</TextLink>
        </p>
    </Form>
</template>