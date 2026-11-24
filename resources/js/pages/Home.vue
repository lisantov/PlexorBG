<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { home } from '@/routes';
import { index as news } from '@/routes/news';
import { index as profile } from '@/routes/profile';
import { index as trainers } from '@/routes/trainers';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Главная', href: home() }],
    },
});
</script>

<template>
    <Head title="Главная" />

    <div class="flex flex-col gap-4 p-2">
        <h1 class="text-2xl font-semibold">Главная</h1>
        <p class="text-muted-foreground">Заглушка главной страницы.</p>
        <ul class="list-inside list-disc text-sm">
            <li>
                <Link :href="news()" class="underline">Новости</Link>
            </li>
            <li>
                <Link :href="trainers()" class="underline">Тренажёры</Link>
            </li>
            <li>
                <Link :href="profile()" class="underline">Профиль</Link>
            </li>
        </ul>
    </div>
</template>