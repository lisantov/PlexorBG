<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { edit } from '@/routes/admin/trainers';
import { index as trainersIndex } from '@/routes/trainers';
import type { TrainerDetail } from '@/types/trainer';

type Props = {
    trainer: TrainerDetail;
};

defineProps<Props>();

const page = usePage();
const isAdmin = computed(() => page.props.auth.isAdmin);
</script>

<template>
    <Head :title="trainer.title" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <Link
            :href="trainersIndex()"
            class="text-muted-foreground mb-6 inline-block text-sm underline"
        >
            ← Все тренажёры
        </Link>

        <article class="mb-8 space-y-3">
            <h1 class="text-2xl font-semibold">{{ trainer.title }}</h1>
            <p class="text-muted-foreground text-sm">{{ trainer.description }}</p>
            <section
                class="border border-border p-4 text-sm whitespace-pre-wrap"
            >
                {{ trainer.content }}
            </section>
            <Link
                v-if="isAdmin"
                :href="edit(trainer.id)"
                class="text-muted-foreground text-xs underline"
            >
                Редактировать
            </Link>
        </article>

        <section class="space-y-3 border-t border-border pt-6">
            <h2 class="text-sm font-medium">Сессия</h2>
            <p class="text-muted-foreground text-xs">
                Отправка решения подключится позже.
            </p>
            <textarea
                class="min-h-40 w-full border border-border bg-background p-3 font-mono text-sm"
                placeholder="// решение"
                disabled
            />
        </section>
    </div>
</template>