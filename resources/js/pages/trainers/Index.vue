<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import TrainerLikeController from '@/actions/App/Http/Controllers/Trainers/LikeController';
import LikeButton from '@/components/LikeButton.vue';
import { create as adminTrainerCreate, edit } from '@/routes/admin/trainers';
import { index as trainersIndex, show } from '@/routes/trainers';
import type { TrainerListItem, TrainerSort } from '@/types/trainer';

type Props = {
    items: TrainerListItem[];
    sort: TrainerSort;
};

defineProps<Props>();

const sortLinkClass = (active: boolean) =>
    active
        ? 'text-foreground font-medium'
        : 'text-muted-foreground hover:text-foreground';

const page = usePage();
const isAdmin = computed(() => page.props.auth.isAdmin);
const user = computed(() => page.props.auth.user);
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

        <div class="mb-6 flex flex-wrap gap-4 text-sm">
            <span class="text-muted-foreground">Сортировка:</span>
            <Link
                :href="trainersIndex({ query: { sort: 'latest' } })"
                :class="sortLinkClass(sort === 'latest')"
                preserve-scroll
            >
                Сначала новые
            </Link>
            <Link
                :href="trainersIndex({ query: { sort: 'likes' } })"
                :class="sortLinkClass(sort === 'likes')"
                preserve-scroll
            >
                По лайкам
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
                <div class="flex flex-wrap items-baseline justify-between gap-2">
                    <Link
                        :href="show(item.id)"
                        class="font-medium underline-offset-4 hover:underline"
                    >
                        {{ item.title }}
                    </Link>
                    <div class="flex items-center gap-3">
                        <LikeButton
                            :likes_count="item.likes_count"
                            :liked="item.liked"
                            :can-like="!!user"
                            :like-form="
                                TrainerLikeController.toggle.form(item.id)
                            "
                        />
                        <Link
                            v-if="isAdmin"
                            :href="edit(item.id)"
                            class="text-muted-foreground text-xs underline"
                        >
                            Изменить
                        </Link>
                    </div>
                </div>
                <p class="text-muted-foreground text-xs">
                    {{ item.description }}
                    <span
                        v-if="item.comments_count !== undefined"
                        class="ml-2"
                    >
                        · {{ item.comments_count }} комм.
                    </span>
                </p>
            </li>
        </ul>
    </div>
</template>