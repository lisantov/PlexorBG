<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import NewsLikeController from '@/actions/App/Http/Controllers/News/LikeController';
import LikeButton from '@/components/LikeButton.vue';
import { create, edit } from '@/routes/admin/news';
import { index as newsIndex, show } from '@/routes/news';
import type { NewsListItem, NewsSort } from '@/types/news';

type Props = {
    items: NewsListItem[];
    sort: NewsSort;
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

        <div class="mb-6 flex flex-wrap gap-4 text-sm">
            <span class="text-muted-foreground">Сортировка:</span>
            <Link
                :href="newsIndex({ query: { sort: 'latest' } })"
                :class="sortLinkClass(sort === 'latest')"
                preserve-scroll
            >
                Сначала новые
            </Link>
            <Link
                :href="newsIndex({ query: { sort: 'likes' } })"
                :class="sortLinkClass(sort === 'likes')"
                preserve-scroll
            >
                По лайкам
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
                    <div class="flex items-center gap-3">
                        <LikeButton
                            :likes_count="item.likes_count"
                            :liked="item.liked"
                            :can-like="!!user"
                            :like-form="
                                NewsLikeController.toggle.form(item.id)
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
                <p class="text-muted-foreground line-clamp-3 whitespace-pre-wrap">
                    {{ item.content }}
                </p>
            </li>
        </ul>
    </div>
</template>