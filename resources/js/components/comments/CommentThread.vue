<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import type { CommentNode } from '@/types/comment';
import type { InertiaFormComponentProps } from '@inertiajs/vue3';

type Props = {
    comments: CommentNode[];
    commentForm: InertiaFormComponentProps;
    canReply: boolean;
    depth?: number;
};

withDefaults(defineProps<Props>(), {
    depth: 0,
});

const replyToId = ref<string | null>(null);

function toggleReply(id: string) {
    replyToId.value = replyToId.value === id ? null : id;
}
</script>

<template>
    <ul class="space-y-4" :class="depth > 0 ? 'mt-3 border-l border-border pl-4' : ''">
        <li
            v-for="comment in comments"
            :key="comment.id"
            class="space-y-2 text-sm"
        >
            <div class="space-y-1">
                <p class="text-muted-foreground text-xs">
                    {{ comment.author ?? '—' }}
                    <span v-if="comment.created_at" class="ml-2">
                        {{
                            new Date(comment.created_at).toLocaleString()
                        }}
                    </span>
                </p>
                <p class="whitespace-pre-wrap">{{ comment.content }}</p>
                <button
                    v-if="canReply"
                    type="button"
                    class="text-muted-foreground text-xs underline"
                    @click="toggleReply(comment.id)"
                >
                    {{ replyToId === comment.id ? 'Отмена' : 'Ответить' }}
                </button>
            </div>

            <Form
                v-if="canReply && replyToId === comment.id"
                v-bind="commentForm"
                reset-on-success
                class="flex flex-col gap-2"
                v-slot="{ errors, processing }"
                @success="replyToId = null"
            >
                <input type="hidden" name="parent_id" :value="comment.id" />
                <textarea
                    name="content"
                    required
                    rows="3"
                    maxlength="2000"
                    class="w-full border border-border bg-background p-2 text-sm"
                    placeholder="Ответ"
                />
                <InputError :message="errors.content" />
                <Button type="submit" :disabled="processing" class="w-fit">
                    Отправить
                </Button>
            </Form>

            <CommentThread
                v-if="comment.children.length"
                :comments="comment.children"
                :comment-form="commentForm"
                :can-reply="canReply"
                :depth="depth + 1"
            />
        </li>
    </ul>
</template>