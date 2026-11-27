<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import NewsController from '@/actions/App/Http/Controllers/Admin/NewsController';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as adminIndex } from '@/routes/admin';
import type { NewsAdminEdit } from '@/types/news';

type Props = {
    news: NewsAdminEdit;
};

const props = defineProps<Props>();
</script>

<template>
    <Head title="Редактировать новость" />

    <div class="mx-auto max-w-lg px-4 py-10">
        <h1 class="mb-6 text-2xl font-semibold">Редактировать новость</h1>

        <Form
            v-bind="NewsController.update.form(props.news.id)"
            class="mb-8 flex flex-col gap-4"
            enctype="multipart/form-data"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="title">Заголовок</Label>
                <Input
                    id="title"
                    name="title"
                    required
                    maxlength="255"
                    :default-value="news.title"
                />
                <InputError :message="errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="image">Изображение</Label>
                <img
                    v-if="news.image_url"
                    :src="news.image_url"
                    :alt="news.title"
                    class="max-h-48 w-full rounded-md border border-border object-cover"
                />
                <Input
                    id="image"
                    name="image"
                    type="file"
                    accept="image/jpeg,image/png,image/webp,image/gif"
                />
                <p class="text-muted-foreground text-xs">
                    JPEG, PNG, WebP или GIF, до 5 МБ. Оставьте пустым, чтобы не
                    менять.
                </p>
                <label
                    v-if="news.image_url"
                    class="flex items-center gap-2 text-sm"
                >
                    <input
                        type="checkbox"
                        name="remove_image"
                        value="1"
                        class="rounded border-border"
                    />
                    Удалить текущее изображение
                </label>
                <InputError :message="errors.image" />
            </div>

            <div class="grid gap-2">
                <Label for="content">Текст</Label>
                <textarea
                    id="content"
                    name="content"
                    required
                    rows="8"
                    class="w-full border border-border bg-background p-2 text-sm"
                    :default-value="news.content"
                />
                <InputError :message="errors.content" />
            </div>

            <div class="flex gap-4">
                <Button type="submit" :disabled="processing">Сохранить</Button>
                <Link
                    :href="adminIndex({ query: { tab: 'news' } })"
                    class="py-2 text-sm underline"
                >
                    Отмена
                </Link>
            </div>
        </Form>

        <ConfirmDeleteDialog
            :form="NewsController.destroy.form(props.news.id)"
            title="Удалить новость?"
            :description="`«${news.title}» будет удалена безвозвратно.`"
        />
    </div>
</template>