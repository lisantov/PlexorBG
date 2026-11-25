<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { edit as settingsEdit } from '@/routes/profile';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Профиль" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-semibold">Профиль</h1>

        <dl v-if="user" class="mb-8 space-y-3 text-sm">
            <div class="flex gap-3">
                <dt class="w-20 text-muted-foreground">Login</dt>
                <dd>{{ user.login }}</dd>
            </div>
            <div class="flex gap-3">
                <dt class="w-20 text-muted-foreground">Email</dt>
                <dd>{{ user.email }}</dd>
            </div>
        </dl>

        <Link
            :href="settingsEdit()"
            class="text-sm text-muted-foreground underline"
        >
            Настройки аккаунта
        </Link>
    </div>
</template>