<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { index as trainersIndex, session } from '@/routes/trainers';

const items = [
    { id: '1', name: 'Тренажёр A' },
    { id: '2', name: 'Тренажёр B' },
    { id: '3', name: 'Тренажёр C' },
];

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Тренажёры', href: trainersIndex() }],
    },
});
</script>

<template>
    <Head title="Тренажёры" />

    <div class="flex flex-col gap-4 p-2">
        <h1 class="text-2xl font-semibold">Тренажёры</h1>
        <p class="text-muted-foreground">Список-заглушка. API не подключён.</p>
        <ul class="divide-y rounded-md border">
            <li
                v-for="item in items"
                :key="item.id"
                class="flex items-center justify-between px-4 py-3 text-sm"
            >
                <span>{{ item.name }}</span>
                <Link :href="session()" class="underline">Открыть сессию</Link>
            </li>
        </ul>
    </div>
</template>