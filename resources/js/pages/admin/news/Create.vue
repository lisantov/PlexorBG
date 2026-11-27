<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import NewsController from '@/actions/App/Http/Controllers/Admin/NewsController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as adminIndex } from '@/routes/admin';
</script>

<template>
    <Head title="Добавить новость" />

    <div class="mx-auto max-w-lg px-4 py-10">
        <h1 class="mb-6 text-2xl font-semibold">Новая новость</h1>

        <Form
            v-bind="NewsController.store.form()"
            class="flex flex-col gap-4"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="title">Заголовок</Label>
                <Input id="title" name="title" required maxlength="255" />
                <InputError :message="errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="content">Текст</Label>
                <textarea
                    id="content"
                    name="content"
                    required
                    rows="8"
                    class="w-full border border-border bg-background p-2 text-sm"
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
    </div>
</template>