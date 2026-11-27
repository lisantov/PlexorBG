<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import NewsLikeController from '@/actions/App/Http/Controllers/News/LikeController';
import LikeButton from '@/components/LikeButton.vue';
import ListFilters from '@/components/list/ListFilters.vue';
import PageContainer from '@/components/layout/PageContainer.vue';
import PageTitle from '@/components/layout/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { create, edit } from '@/routes/admin/news';
import { index as newsIndex, show } from '@/routes/news';
import type { NewsListItem, NewsSort } from '@/types/news';

type Props = {
    items: NewsListItem[];
    sort: NewsSort;
    search: string;
};

const props = defineProps<Props>();

const listQuery = (sort: NewsSort) => ({
    sort,
    ...(props.search !== '' ? { search: props.search } : {}),
});

const sortOptions = [
    { value: 'latest', label: 'Сначала новые' },
    { value: 'likes', label: 'По лайкам' },
] as const;

const page = usePage();
const isAdmin = computed(() => page.props.auth.isAdmin);
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Новости" />

    <PageContainer>
        <PageTitle
            title="Новости"
            description="Лента публикаций и анонсов с арены PlexorBG."
        >
            <template v-if="isAdmin" #actions>
                <Button as-child variant="secondary">
                    <Link :href="create()">Добавить</Link>
                </Button>
            </template>
        </PageTitle>

        <ListFilters
            :form="newsIndex.form()"
            :search="search"
            :sort="sort"
            :sort-options="[...sortOptions]"
            :sort-href="(value) => newsIndex({ query: listQuery(value as NewsSort) })"
            :reset-href="newsIndex({ query: { sort } })"
        />

        <p
            v-if="items.length === 0"
            class="text-muted-foreground text-base"
        >
            <template v-if="search !== ''">
                Ничего не найдено по запросу «{{ search }}».
            </template>
            <template v-else>Пока нет новостей.</template>
        </p>

        <div v-else class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <article
                v-for="item in items"
                :key="item.id"
                class="flex flex-col overflow-hidden rounded-2xl border-2 border-border/80 bg-card/50 shadow-lg transition hover:border-primary/40"
            >
                <Link
                    v-if="item.image_url"
                    :href="show(item.id)"
                    class="block aspect-[16/10] overflow-hidden border-b border-border/60"
                >
                    <img
                        :src="item.image_url"
                        :alt="item.title"
                        class="size-full object-cover transition hover:scale-105"
                    />
                </Link>
                <div class="flex flex-1 flex-col gap-3 p-5 text-sm">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div class="space-y-1">
                        <Link
                            :href="show(item.id)"
                            class="text-lg font-semibold underline-offset-4 hover:text-primary hover:underline"
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
                <p class="text-muted-foreground line-clamp-4 flex-1 whitespace-pre-wrap leading-relaxed">
                    {{ item.content }}
                </p>
                </div>
            </article>
        </div>
    </PageContainer>
</template>