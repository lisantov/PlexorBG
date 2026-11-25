<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { session } from '@/routes/trainers';

const items = [
    { id: '1', name: 'Тренажёр A' },
    { id: '2', name: 'Тренажёр B' },
    { id: '3', name: 'Тренажёр C' },
];
</script>

<template>
    <Head title="Тренажёры" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-semibold">Тренажёры</h1>

        <ul class="divide-y divide-border border border-border text-sm">
            <li
                v-for="item in items"
                :key="item.id"
                class="flex items-center justify-between px-4 py-3"
            >
                <span>{{ item.name }}</span>
                <Link :href="session()" class="text-muted-foreground underline">
                    Сессия
                </Link>
            </li>
        </ul>
    </div>
</template>