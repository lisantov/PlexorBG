<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { create as adminTrainerCreate } from '@/routes/admin/trainers';
import { show } from '@/routes/trainers';
import type { TrainerListItem } from '@/types/trainer';

type Props = {
    items: TrainerListItem[];
};

defineProps<Props>();

const page = usePage();
const isAdmin = computed(() => page.props.auth.isAdmin);
</script>

<template>
    <Head title="Тренажёры" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <div class="mb-6 flex items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold">Тренажёры</h1>
            <Link
                v-if="isAdmin"
                :href="adminTrainerCreate()"
                class="text-sm text-muted-foreground underline"
            >
                Добавить
            </Link>
        </div>

        <p v-if="items.length === 0" class="text-muted-foreground text-sm">
            Пока нет опубликованных тренажёров.
        </p>

        <ul v-else class="divide-y divide-border border border-border text-sm">
            <li
                v-for="item in items"
                :key="item.id"
                class="space-y-1 px-4 py-3"
            >
                <Link
                    :href="show(item.id)"
                    class="font-medium underline-offset-4 hover:underline"
                >
                    {{ item.title }}
                </Link>
                <p class="text-muted-foreground text-xs">
                    {{ item.description }}
                </p>
            </li>
        </ul>
    </div>
</template>