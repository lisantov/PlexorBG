<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import TrainerLikeController from '@/actions/App/Http/Controllers/Trainers/LikeController';
import LikeButton from '@/components/LikeButton.vue';
import ListFilters from '@/components/list/ListFilters.vue';
import PageContainer from '@/components/layout/PageContainer.vue';
import PageTitle from '@/components/layout/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { create as adminTrainerCreate, edit } from '@/routes/admin/trainers';
import { index as trainersIndex, show } from '@/routes/trainers';
import type { TrainerListItem, TrainerSort } from '@/types/trainer';

type Props = {
    items: TrainerListItem[];
    sort: TrainerSort;
    search: string;
};

const props = defineProps<Props>();

const listQuery = (sort: TrainerSort) => ({
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
    <Head title="Тренажёры" />

    <PageContainer>
        <PageTitle
            title="Тренажёры"
            description="Задачи на арене — решай, лайкай и обсуждай подходы."
        >
            <template v-if="isAdmin" #actions>
                <Button as-child variant="secondary">
                    <Link :href="adminTrainerCreate()">Добавить</Link>
                </Button>
            </template>
        </PageTitle>

        <ListFilters
            :form="trainersIndex.form()"
            :search="search"
            :sort="sort"
            :sort-options="[...sortOptions]"
            :sort-href="
                (value) =>
                    trainersIndex({ query: listQuery(value as TrainerSort) })
            "
            :reset-href="trainersIndex({ query: { sort } })"
        />

        <p
            v-if="items.length === 0"
            class="text-muted-foreground text-base"
        >
            <template v-if="search !== ''">
                Ничего не найдено по запросу «{{ search }}».
            </template>
            <template v-else>Пока нет опубликованных тренажёров.</template>
        </p>

        <div v-else class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <article
                v-for="item in items"
                :key="item.id"
                class="flex flex-col rounded-2xl border-2 border-border/80 bg-card/50 p-6 shadow-lg transition hover:border-primary/40"
            >
                <div
                    class="mb-3 flex flex-wrap items-start justify-between gap-2"
                >
                    <Link
                        :href="show(item.id)"
                        class="text-lg font-semibold underline-offset-4 hover:text-primary hover:underline"
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
                <p class="text-muted-foreground mb-4 flex-1 text-sm leading-relaxed">
                    {{ item.description }}
                </p>
                <p class="text-muted-foreground text-xs">
                    <span
                        v-if="item.comments_count !== undefined"
                    >
                        {{ item.comments_count }} комм.
                    </span>
                </p>
            </article>
        </div>
    </PageContainer>
</template>