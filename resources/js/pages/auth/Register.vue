<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import RegisteredUserController from '@/actions/App/Http/Controllers/Auth/RegisteredUserController';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';

defineOptions({
    layout: {
        title: 'Create an account',
        description: 'Enter your details below to register',
    },
});
</script>

<template>
    <Head title="Register" />

    <Form
        v-bind="RegisteredUserController.store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        class="flex flex-col gap-6"
        v-slot="{ errors, processing }"
    >
        <div class="grid gap-2">
            <Label for="login">Login</Label>
            <Input
                id="login"
                type="text"
                name="login"
                required
                autofocus
                autocomplete="username"
                placeholder="Login"
            />
            <InputError :message="errors.login" />
        </div>

        <div class="grid gap-2">
            <Label for="email">Email address</Label>
            <Input
                id="email"
                type="email"
                name="email"
                required
                autocomplete="email"
                placeholder="Email address"
            />
            <InputError :message="errors.email" />
        </div>

        <div class="grid gap-2">
            <Label for="password">Password</Label>
            <PasswordInput
                id="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Password"
            />
            <InputError :message="errors.password" />
        </div>

        <div class="grid gap-2">
            <Label for="password_confirmation">Confirm password</Label>
            <PasswordInput
                id="password_confirmation"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm password"
            />
            <InputError :message="errors.password_confirmation" />
        </div>

        <Button type="submit" class="w-full" :disabled="processing">
            Create account
        </Button>

        <p class="text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink :href="login()">Log in</TextLink>
        </p>
    </Form>
</template>