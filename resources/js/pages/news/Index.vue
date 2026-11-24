<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { index as newsIndex } from '@/routes/news';

const items = [
    { id: '1', title: 'Новость 1', date: '2026-11-01' },
    { id: '2', title: 'Новость 2', date: '2026-11-10' },
    { id: '3', title: 'Новость 3', date: '2026-11-20' },
];

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Новости', href: newsIndex() }],
    },
});
</script>

<template>
    <Head title="Новости" />

    <div class="flex flex-col gap-4 p-2">
        <h1 class="text-2xl font-semibold">Новости</h1>
        <p class="text-muted-foreground">Список-заглушка. API не подключён.</p>
        <ul class="divide-y rounded-md border">
            <li
                v-for="item in items"
                :key="item.id"
                class="px-4 py-3 text-sm"
            >
                <span class="font-medium">{{ item.title }}</span>
                <span class="ml-2 text-muted-foreground">{{ item.date }}</span>
            </li>
        </ul>
    </div>
</template>