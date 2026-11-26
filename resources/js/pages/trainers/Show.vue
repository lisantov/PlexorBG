<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import TrainerCommentaryController from '@/actions/App/Http/Controllers/Trainers/CommentaryController';
import TrainerLikeController from '@/actions/App/Http/Controllers/Trainers/LikeController';
import CommentThread from '@/components/comments/CommentThread.vue';
import LikeButton from '@/components/LikeButton.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { login } from '@/routes';
import { edit } from '@/routes/admin/trainers';
import { index as trainersIndex } from '@/routes/trainers';
import type { CommentNode } from '@/types/comment';
import type { TrainerDetail } from '@/types/trainer';

type Props = {
    trainer: TrainerDetail;
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

        <article class="mb-8 space-y-3 border-b border-border pb-8">
            <h1 class="text-2xl font-semibold">{{ trainer.title }}</h1>
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

        <section class="mb-10 space-y-3">
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

        <section class="space-y-6 border-t border-border pt-8">
            <h2 class="text-lg font-medium">
                Комментарии ({{ comments_count }})
            </h2>

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
        </section>
    </div>
</template>