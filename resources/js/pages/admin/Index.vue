<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import NewsController from '@/actions/App/Http/Controllers/Admin/NewsController';
import TrainerController from '@/actions/App/Http/Controllers/Admin/TrainerController';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import { Button } from '@/components/ui/button';
import { index as adminIndex } from '@/routes/admin';
import { create as adminNewsCreate, edit as adminNewsEdit } from '@/routes/admin/news';
import {
    create as adminTrainerCreate,
    edit as adminTrainerEdit,
} from '@/routes/admin/trainers';
import type {
    AdminNewsListItem,
    AdminTab,
    AdminTrainerListItem,
} from '@/types/admin';

type Props = {
    tab: AdminTab;
    news: AdminNewsListItem[];
    trainers: AdminTrainerListItem[];
};

defineProps<Props>();

const tabLinkClass = (active: boolean) =>
    [
        'border-b-2 px-1 pb-2 text-sm transition-colors',
        active
            ? 'border-foreground text-foreground font-medium'
            : 'border-transparent text-muted-foreground hover:text-foreground',
    ];

const formatDate = (value: string | null) =>
    value ? new Date(value).toLocaleString() : '—';
</script>

<template>
    <Head title="Админ-панель" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-semibold">Админ-панель</h1>

        <nav class="mb-8 flex gap-6 border-b border-border">
            <Link
                :href="adminIndex({ query: { tab: 'news' } })"
                :class="tabLinkClass(tab === 'news')"
            >
                Новости
            </Link>
            <Link
                :href="adminIndex({ query: { tab: 'trainers' } })"
                :class="tabLinkClass(tab === 'trainers')"
            >
                Тренажёры
            </Link>
        </nav>

        <section v-if="tab === 'news'" class="space-y-4">
            <div class="flex items-center justify-between gap-2">
                <p class="text-muted-foreground text-sm">
                    Всего: {{ news.length }}
                </p>
                <Button as-child>
                    <Link :href="adminNewsCreate()">Добавить новость</Link>
                </Button>
            </div>

            <p
                v-if="news.length === 0"
                class="text-muted-foreground border border-border px-4 py-8 text-center text-sm"
            >
                Новостей пока нет.
            </p>

            <ul
                v-else
                class="divide-y divide-border border border-border text-sm"
            >
                <li
                    v-for="item in news"
                    :key="item.id"
                    class="flex flex-wrap items-center justify-between gap-3 px-4 py-3"
                >
                    <div class="min-w-0 space-y-1">
                        <p class="font-medium">{{ item.title }}</p>
                        <p class="text-muted-foreground text-xs">
                            <span v-if="item.author">{{ item.author }}</span>
                            <span class="ml-2">{{ formatDate(item.created_at) }}</span>
                        </p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <Button as-child variant="outline" size="sm">
                            <Link :href="adminNewsEdit(item.id)">Изменить</Link>
                        </Button>
                        <ConfirmDeleteDialog
                            :form="NewsController.destroy.form(item.id)"
                            title="Удалить новость?"
                            :description="`«${item.title}» будет удалена безвозвратно.`"
                            size="sm"
                        />
                    </div>
                </li>
            </ul>
        </section>

        <section v-else class="space-y-4">
            <div class="flex items-center justify-between gap-2">
                <p class="text-muted-foreground text-sm">
                    Всего: {{ trainers.length }}
                </p>
                <Button as-child>
                    <Link :href="adminTrainerCreate()">Добавить тренажёр</Link>
                </Button>
            </div>

            <p
                v-if="trainers.length === 0"
                class="text-muted-foreground border border-border px-4 py-8 text-center text-sm"
            >
                Тренажёров пока нет.
            </p>

            <ul
                v-else
                class="divide-y divide-border border border-border text-sm"
            >
                <li
                    v-for="item in trainers"
                    :key="item.id"
                    class="flex flex-wrap items-center justify-between gap-3 px-4 py-3"
                >
                    <div class="min-w-0 space-y-1">
                        <p class="font-medium">{{ item.title }}</p>
                        <p class="text-muted-foreground text-xs">
                            {{ item.description }}
                        </p>
                        <p class="text-muted-foreground text-xs">
                            {{ item.status }} · {{ formatDate(item.created_at) }}
                        </p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <Button as-child variant="outline" size="sm">
                            <Link :href="adminTrainerEdit(item.id)">
                                Изменить
                            </Link>
                        </Button>
                        <ConfirmDeleteDialog
                            :form="
                                TrainerController.destroy.form(item.id)
                            "
                            title="Удалить тренажёр?"
                            :description="`«${item.title}» будет удалён безвозвратно.`"
                            size="sm"
                        />
                    </div>
                </li>
            </ul>
        </section>
    </div>
</template>