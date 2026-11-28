<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import TrainerCommentaryController from '@/actions/App/Http/Controllers/Trainers/CommentaryController';
import TrainerLikeController from '@/actions/App/Http/Controllers/Trainers/LikeController';
import CommentThread from '@/components/comments/CommentThread.vue';
import LikeButton from '@/components/LikeButton.vue';
import PageContainer from '@/components/layout/PageContainer.vue';
import PagePanel from '@/components/layout/PagePanel.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { login } from '@/routes';
import { edit } from '@/routes/admin/trainers';
import {
    index as trainersIndex,
    show as trainerShow,
    solve as trainerSolve,
} from '@/routes/trainers';
import type { CommentNode } from '@/types/comment';
import TrainerSolutionCard from '@/components/trainers/TrainerSolutionCard.vue';
import type {
    TrainerShowTab,
    TrainerSolutionListItem,
} from '@/types/trainer-solution';
import type { TrainerDetail } from '@/types/trainer';

type Props = {
    tab: TrainerShowTab;
    trainer: TrainerDetail;
    solutions: TrainerSolutionListItem[];
    solutions_count: number;
    comments: CommentNode[];
    comments_count: number;
};

const props = defineProps<Props>();

const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => page.props.auth.isAdmin);
const commentForm = computed(() =>
    TrainerCommentaryController.store.form(props.trainer.id),
);

const tabLinkClass = (active: boolean) =>
    [
        'border-b-2 px-1 pb-2 text-sm transition-colors',
        active
            ? 'border-primary text-primary font-medium'
            : 'border-transparent text-muted-foreground hover:text-foreground',
    ];

const tabHref = (value: TrainerShowTab) =>
    trainerShow(props.trainer.id, { query: { tab: value } });
</script>

<template>
    <Head :title="trainer.title" />

    <PageContainer narrow>
        <Link
            :href="trainersIndex()"
            class="text-muted-foreground mb-6 inline-block text-sm hover:text-primary"
        >
            ← Все тренажёры
        </Link>

        <PagePanel class="mb-8 space-y-5">
            <h1 class="text-3xl font-bold tracking-tight lg:text-4xl">
                {{ trainer.title }}
            </h1>
            <div
                class="flex flex-wrap items-center gap-3 text-sm text-muted-foreground"
            >
                <p>{{ trainer.description }}</p>
                <LikeButton
                    :likes_count="trainer.likes_count"
                    :liked="trainer.liked"
                    :can-like="!!user"
                    :like-form="
                        TrainerLikeController.toggle.form(trainer.id)
                    "
                />
            </div>
        </PagePanel>

        <nav class="mb-8 flex flex-wrap gap-6 border-b-2 border-border/80">
            <Link :href="tabHref('task')" :class="tabLinkClass(tab === 'task')">
                Задача
            </Link>
            <Link
                :href="tabHref('solutions')"
                :class="tabLinkClass(tab === 'solutions')"
            >
                Решения ({{ solutions_count }})
            </Link>
            <Link
                :href="tabHref('comments')"
                :class="tabLinkClass(tab === 'comments')"
            >
                Комментарии ({{ comments_count }})
            </Link>
        </nav>

        <PagePanel v-if="tab === 'task'" class="mb-8 space-y-5">
            <section
                class="rounded-xl border-2 border-border/80 bg-background/50 p-5 text-base leading-relaxed whitespace-pre-wrap"
            >
                {{ trainer.content }}
            </section>
            <div class="flex flex-wrap items-center gap-3">
                <Button v-if="user" as-child>
                    <Link :href="trainerSolve(trainer.id)">Решить задачу</Link>
                </Button>
                <p v-else class="text-muted-foreground text-sm">
                    <Link :href="login()" class="underline">Войдите</Link>, чтобы
                    решать задачу.
                </p>
                <Link
                    v-if="isAdmin"
                    :href="edit(trainer.id)"
                    class="text-muted-foreground text-sm underline hover:text-primary"
                >
                    Редактировать
                </Link>
            </div>
        </PagePanel>

        <PagePanel v-else-if="tab === 'solutions'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-muted-foreground text-sm">
                    Все отправленные решения по этому тренажёру.
                </p>
                <Button v-if="user" as-child variant="secondary" size="sm">
                    <Link :href="trainerSolve(trainer.id)">Решить снова</Link>
                </Button>
            </div>

            <p
                v-if="solutions.length === 0"
                class="text-muted-foreground border border-border px-4 py-8 text-center text-sm"
            >
                Пока нет отправленных решений.
                <Link
                    v-if="user"
                    :href="trainerSolve(trainer.id)"
                    class="text-primary ml-1 underline"
                >
                    Стать первым
                </Link>
            </p>

            <ul
                v-else
                class="divide-y divide-border border border-border text-sm"
            >
                <TrainerSolutionCard
                    v-for="item in solutions"
                    :key="item.id"
                    :solution="item"
                    :can-interact="!!user"
                />
            </ul>
        </PagePanel>

        <PagePanel v-else class="space-y-6">
            <CommentThread
                v-if="comments.length"
                :comments="comments"
                :comment-form="commentForm"
                :can-reply="!!user"
            />
            <p v-else class="text-muted-foreground text-sm">
                Пока нет комментариев.
            </p>

            <div v-if="user" class="border-t border-border pt-6">
                <Form
                    v-bind="commentForm"
                    reset-on-success
                    class="flex flex-col gap-3"
                    v-slot="{ errors, processing }"
                >
                    <label class="text-sm font-medium" for="trainer-comment">
                        Комментарий к тренажёру
                    </label>
                    <textarea
                        id="trainer-comment"
                        name="content"
                        required
                        rows="4"
                        maxlength="2000"
                        class="w-full border border-border bg-background p-2 text-sm"
                        placeholder="Текст комментария"
                    />
                    <InputError :message="errors.content" />
                    <Button type="submit" :disabled="processing" class="w-fit">
                        Отправить
                    </Button>
                </Form>
            </div>
            <p
                v-else
                class="text-muted-foreground border-t border-border pt-6 text-sm"
            >
                <Link :href="login()" class="underline">Войдите</Link>, чтобы
                оставить комментарий.
            </p>
        </PagePanel>
    </PageContainer>
</template>