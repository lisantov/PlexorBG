<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import SolutionCommentaryController from '@/actions/App/Http/Controllers/Trainers/SolutionCommentaryController';
import SolutionLikeController from '@/actions/App/Http/Controllers/Trainers/SolutionLikeController';
import CommentThread from '@/components/comments/CommentThread.vue';
import InputError from '@/components/InputError.vue';
import LikeButton from '@/components/LikeButton.vue';
import { Button } from '@/components/ui/button';
import type { TrainerSolutionListItem } from '@/types/trainer-solution';

type Props = {
    solution: TrainerSolutionListItem;
    canInteract: boolean;
};

const props = defineProps<Props>();

const commentForm = SolutionCommentaryController.store.form(props.solution.id);

const formatDate = (value: string | null) =>
    value ? new Date(value).toLocaleString() : '—';
</script>

<template>
    <li class="space-y-4 px-4 py-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-muted-foreground text-xs">
                <span
                    v-if="solution.author"
                    class="font-medium text-foreground"
                    >{{ solution.author }}</span
                >
                <span class="ml-2">{{ formatDate(solution.created_at) }}</span>
            </p>
            <LikeButton
                :likes_count="solution.likes_count"
                :liked="solution.liked"
                :can-like="canInteract"
                :like-form="
                    SolutionLikeController.toggle.form(solution.id)
                "
            />
        </div>

        <pre
            class="overflow-x-auto rounded-md border border-border bg-muted/30 p-3 font-mono text-xs whitespace-pre-wrap"
            >{{ solution.code ?? '' }}</pre
        >

        <div class="space-y-4 border-t border-border pt-4">
            <h3 class="text-sm font-medium">
                Комментарии ({{ solution.comments_count }})
            </h3>

            <CommentThread
                v-if="solution.comments.length"
                :comments="solution.comments"
                :comment-form="commentForm"
                :can-reply="canInteract"
            />
            <p v-else class="text-muted-foreground text-sm">
                Пока нет комментариев.
            </p>

            <Form
                v-if="canInteract"
                v-bind="commentForm"
                reset-on-success
                class="flex flex-col gap-2"
                preserve-scroll
                v-slot="{ errors, processing }"
            >
                <label
                    class="text-sm font-medium"
                    :for="`solution-comment-${solution.id}`"
                >
                    Комментарий к решению
                </label>
                <textarea
                    :id="`solution-comment-${solution.id}`"
                    name="content"
                    required
                    rows="3"
                    maxlength="2000"
                    class="w-full border border-border bg-background p-2 text-sm"
                    placeholder="Текст комментария"
                />
                <InputError :message="errors.content" />
                <Button type="submit" :disabled="processing" size="sm" class="w-fit">
                    Отправить
                </Button>
            </Form>
        </div>
    </li>
</template>