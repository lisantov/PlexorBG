<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { index as trainersIndex, session } from '@/routes/trainers';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Тренажёры', href: trainersIndex() },
            { title: 'Сессия', href: session() },
        ],
    },
});
</script>

<template>
    <Head title="Сессия тренажёра" />

    <div class="flex max-w-2xl flex-col gap-4 p-2">
        <h1 class="text-2xl font-semibold">Сессия решения тренажёра</h1>
        <p class="text-muted-foreground">
            Заглушка сессии. Данные задания и отправка решения не подключены.
        </p>

        <section class="rounded-md border p-4 text-sm">
            <h2 class="mb-2 font-medium">Задание (пример)</h2>
            <p>Реализуйте функцию, которая возвращает сумму двух чисел.</p>
        </section>

        <label class="flex flex-col gap-1 text-sm">
            <span class="font-medium">Решение</span>
            <textarea
                class="min-h-32 rounded-md border bg-background p-2 font-mono text-sm"
                placeholder="// код"
                disabled
            />
        </label>

        <div class="flex gap-3">
            <button
                type="button"
                class="rounded-md border px-4 py-2 text-sm opacity-50"
                disabled
            >
                Отправить
            </button>
            <Link :href="trainersIndex()" class="py-2 text-sm underline">
                К списку тренажёров
            </Link>
        </div>
    </div>
</template>