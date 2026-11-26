<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import CommentaryController from '@/actions/App/Http/Controllers/News/CommentaryController';
import NewsLikeController from '@/actions/App/Http/Controllers/News/LikeController';
import CommentThread from '@/components/comments/CommentThread.vue';
import LikeButton from '@/components/LikeButton.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { login } from '@/routes';
import { edit } from '@/routes/admin/news';
import { index as newsIndex } from '@/routes/news';
import type { CommentNode } from '@/types/comment';
import type { NewsDetail } from '@/types/news';

type Props = {
    news: NewsDetail;
    comments: CommentNode[];
    comments_count: number;
};

defineProps<Props>();

const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => page.props.auth.isAdmin);
</script>

<template>
    <Head :title="news.title" />

    <div class="mx-auto max-w-3xl px-4 py-10">
        <Link
            :href="newsIndex()"
            class="text-muted-foreground mb-6 inline-block text-sm underline"
        >
            ← Все новости
        </Link>

        <article class="mb-10 space-y-3 border-b border-border pb-8">
            <h1 class="text-2xl font-semibold">{{ news.title }}</h1>
            <div
                class="flex flex-wrap items-center gap-3 text-xs text-muted-foreground"
            >
                <span v-if="news.author">{{ news.author }}</span>
                <span v-if="news.created_at">
                    {{ new Date(news.created_at).toLocaleString() }}
                </span>
                <LikeButton
                    :likes_count="news.likes_count"
                    :liked="news.liked"
                    :can-like="!!user"
                    :like-form="NewsLikeController.toggle.form(news.id)"
                />
            </div>
            <p class="text-sm whitespace-pre-wrap">{{ news.content }}</p>
            <Link
                v-if="isAdmin"
                :href="edit(news.id)"
                class="text-muted-foreground text-xs underline"
            >
                Редактировать
            </Link>
        </article>

        <section class="space-y-6">
            <h2 class="text-lg font-medium">
                Комментарии ({{ comments_count }})
            </h2>

            <CommentThread
                v-if="comments.length"
                :comments="comments"
                :comment-form="CommentaryController.store.form(news.id)"
                :can-reply="!!user"
            />
            <p v-else class="text-muted-foreground text-sm">
                Пока нет комментариев.
            </p>

            <div v-if="user" class="border-t border-border pt-6">
                <Form
                    v-bind="CommentaryController.store.form(news.id)"
                    reset-on-success
                    class="flex flex-col gap-3"
                    v-slot="{ errors, processing }"
                >
                    <label class="text-sm font-medium" for="content">
                        Комментарий к новости
                    </label>
                    <textarea
                        id="content"
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