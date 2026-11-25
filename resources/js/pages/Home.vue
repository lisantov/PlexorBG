<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { index as news } from '@/routes/news';
import { index as trainers } from '@/routes/trainers';
</script>

<template>
    <Head title="Главная" />

    <div class="mx-auto flex max-w-3xl flex-col gap-8 px-4 py-16">
        <div class="space-y-3">
            <h1 class="text-3xl font-semibold tracking-tight">
                Платформа для практики
            </h1>
            <p class="text-muted-foreground max-w-xl text-sm leading-relaxed">
                Новости, задания и тренажёры. Минимальный прототип интерфейса —
                без лишнего оформления.
            </p>
        </div>

        <div class="flex flex-wrap gap-4 text-sm">
            <Link
                :href="news()"
                class="border border-border px-4 py-2 hover:bg-muted/40"
            >
                Новости
            </Link>
            <Link
                :href="trainers()"
                class="border border-border px-4 py-2 hover:bg-muted/40"
            >
                Тренажёры
            </Link>
        </div>
    </div>
</template>