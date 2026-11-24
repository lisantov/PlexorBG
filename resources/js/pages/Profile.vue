<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { index as profileIndex } from '@/routes/profile';

const page = usePage();
const user = computed(() => page.props.auth.user);

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Профиль', href: profileIndex() }],
    },
});
</script>

<template>
    <Head title="Профиль" />

    <div class="flex flex-col gap-4 p-2">
        <h1 class="text-2xl font-semibold">Профиль</h1>
        <p class="text-muted-foreground">Заглушка страницы профиля.</p>
        <dl v-if="user" class="max-w-md space-y-2 text-sm">
            <div class="flex gap-2">
                <dt class="text-muted-foreground">Login:</dt>
                <dd>{{ user.login }}</dd>
            </div>
            <div class="flex gap-2">
                <dt class="text-muted-foreground">Email:</dt>
                <dd>{{ user.email }}</dd>
            </div>
        </dl>
    </div>
</template>