<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import NewsController from '@/actions/App/Http/Controllers/Admin/NewsController';
import TrainerController from '@/actions/App/Http/Controllers/Admin/TrainerController';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import PageContainer from '@/components/layout/PageContainer.vue';
import PagePanel from '@/components/layout/PagePanel.vue';
import PageTitle from '@/components/layout/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { index as adminIndex } from '@/routes/admin';
import { create as adminNewsCreate, edit as adminNewsEdit } from '@/routes/admin/news';
import {
    create as adminTrainerCreate,
    edit as adminTrainerEdit,
} from '@/routes/admin/trainers';
import { show as adminApplicationShow } from '@/routes/admin/trainer-applications';
import type {
    AdminNewsListItem,
    AdminTab,
    AdminTrainerApplicationListItem,
    AdminTrainerListItem,
} from '@/types/admin';

type Props = {
    tab: AdminTab;
    news: AdminNewsListItem[];
    trainers: AdminTrainerListItem[];
    trainerApplications: AdminTrainerApplicationListItem[];
};

defineProps<Props>();

const tabLinkClass = (active: boolean) =>
    [
        'border-b-2 px-1 pb-2 text-sm transition-colors',
        active
            ? 'border-primary text-primary font-medium'
            : 'border-transparent text-muted-foreground hover:text-foreground',
    ];

const formatDate = (value: string | null) =>
    value ? new Date(value).toLocaleString() : '—';
</script>

<template>
    <Head title="Админ-панель" />

    <PageContainer>
        <PageTitle
            title="Админ-панель"
            description="Новости, тренажёры и заявки от пользователей."
        />

        <nav class="mb-8 flex gap-8 border-b-2 border-border/80">
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
            <Link
                :href="adminIndex({ query: { tab: 'applications' } })"
                :class="tabLinkClass(tab === 'applications')"
            >
                Заявки · тренажёры
            </Link>
        </nav>

        <PagePanel v-if="tab === 'news'" class="space-y-4">
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
        </PagePanel>

        <PagePanel v-else-if="tab === 'trainers'" class="space-y-4">
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
                            {{ formatDate(item.created_at) }}
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
        </PagePanel>

        <PagePanel v-else class="space-y-4">
            <p class="text-muted-foreground text-sm">
                Ожидают решения: {{ trainerApplications.length }}
            </p>

            <p
                v-if="trainerApplications.length === 0"
                class="text-muted-foreground border border-border px-4 py-8 text-center text-sm"
            >
                Нет заявок на рассмотрении.
            </p>

            <ul
                v-else
                class="divide-y divide-border border border-border text-sm"
            >
                <li
                    v-for="item in trainerApplications"
                    :key="item.id"
                    class="flex flex-wrap items-center justify-between gap-3 px-4 py-3"
                >
                    <div class="min-w-0 space-y-1">
                        <p class="font-medium">{{ item.title }}</p>
                        <p class="text-muted-foreground text-xs">
                            {{ item.description }}
                        </p>
                        <p class="text-muted-foreground text-xs">
                            <span v-if="item.author">{{ item.author }}</span>
                            <span class="ml-2">{{
                                formatDate(item.created_at)
                            }}</span>
                        </p>
                    </div>
                    <Button as-child variant="outline" size="sm">
                        <Link :href="adminApplicationShow(item.id)">
                            Открыть
                        </Link>
                    </Button>
                </li>
            </ul>
        </PagePanel>
    </PageContainer>
</template>