<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { create, edit } from '@/routes/admin/news';
import { show } from '@/routes/news';
import type { NewsListItem } from '@/types/news';

type Props = {
    items: NewsListItem[];
};

defineProps<Props>();

const page = usePage();
const isAdmin = computed(() => page.props.auth.isAdmin);
</script>

<template>
    <Head title="Новости" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <div class="mb-6 flex items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold">Новости</h1>
            <Link
                v-if="isAdmin"
                :href="create()"
                class="text-sm text-muted-foreground underline"
            >
                Добавить
            </Link>
        </div>

        <p v-if="items.length === 0" class="text-muted-foreground text-sm">
            Пока нет новостей.
        </p>

        <ul v-else class="divide-y divide-border border border-border">
            <li
                v-for="item in items"
                :key="item.id"
                class="space-y-2 px-4 py-4 text-sm"
            >
                <div class="flex flex-wrap items-baseline justify-between gap-2">
                    <div class="space-y-1">
                        <Link
                            :href="show(item.id)"
                            class="font-medium underline-offset-4 hover:underline"
                        >
                            {{ item.title }}
                        </Link>
                        <p class="text-muted-foreground text-xs">
                            <span v-if="item.author">{{ item.author }}</span>
                            <span v-if="item.created_at" class="ml-2">
                                {{
                                    new Date(
                                        item.created_at,
                                    ).toLocaleDateString()
                                }}
                            </span>
                            <span
                                v-if="item.comments_count !== undefined"
                                class="ml-2"
                            >
                                · {{ item.comments_count }} комм.
                            </span>
                        </p>
                    </div>
                    <Link
                        v-if="isAdmin"
                        :href="edit(item.id)"
                        class="text-muted-foreground text-xs underline"
                    >
                        Изменить
                    </Link>
                </div>
                <p class="text-muted-foreground line-clamp-3 whitespace-pre-wrap">
                    {{ item.content }}
                </p>
            </li>
        </ul>
    </div>
</template>