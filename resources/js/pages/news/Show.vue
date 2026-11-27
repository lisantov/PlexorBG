<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import CommentaryController from '@/actions/App/Http/Controllers/News/CommentaryController';
import NewsLikeController from '@/actions/App/Http/Controllers/News/LikeController';
import CommentThread from '@/components/comments/CommentThread.vue';
import LikeButton from '@/components/LikeButton.vue';
import PageContainer from '@/components/layout/PageContainer.vue';
import PagePanel from '@/components/layout/PagePanel.vue';
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

    <PageContainer narrow>
        <Link
            :href="newsIndex()"
            class="text-muted-foreground mb-6 inline-block text-sm hover:text-primary"
        >
            ← Все новости
        </Link>

        <PagePanel class="mb-8 space-y-5">
            <h1 class="text-3xl font-bold tracking-tight lg:text-4xl">
                {{ news.title }}
            </h1>
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
            <img
                v-if="news.image_url"
                :src="news.image_url"
                :alt="news.title"
                class="max-h-[28rem] w-full rounded-xl border-2 border-border/80 object-cover"
            />
            <p class="text-base leading-relaxed whitespace-pre-wrap">
                {{ news.content }}
            </p>
            <Link
                v-if="isAdmin"
                :href="edit(news.id)"
                class="text-muted-foreground text-sm underline hover:text-primary"
            >
                Редактировать
            </Link>
        </PagePanel>

        <PagePanel class="space-y-6">
            <h2 class="text-xl font-semibold">
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
        </PagePanel>
    </PageContainer>
</template>