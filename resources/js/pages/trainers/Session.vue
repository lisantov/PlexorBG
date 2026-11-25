<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { index as trainersIndex } from '@/routes/trainers';
</script>

<template>
    <Head title="Сессия" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-4 text-2xl font-semibold">Сессия тренажёра</h1>
        <p class="text-muted-foreground mb-6 text-sm">Заглушка без отправки решения.</p>

        <section class="mb-4 border border-border p-4 text-sm">
            <h2 class="mb-2 font-medium">Задание</h2>
            <p>Реализуйте функцию, которая возвращает сумму двух чисел.</p>
        </section>

        <textarea
            class="mb-4 min-h-40 w-full border border-border bg-background p-3 font-mono text-sm"
            placeholder="// код"
            disabled
        />

        <Link :href="trainersIndex()" class="text-sm underline">
            Назад к тренажёрам
        </Link>
    </div>
</template>