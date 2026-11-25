<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import TrainerController from '@/actions/App/Http/Controllers/Admin/TrainerController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as trainersIndex } from '@/routes/trainers';
</script>

<template>
    <Head title="Новый тренажёр" />

    <div class="mx-auto max-w-lg px-4 py-10">
        <h1 class="mb-6 text-2xl font-semibold">Новый тренажёр</h1>

        <Form
            v-bind="TrainerController.store.form()"
            class="flex flex-col gap-4"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="title">Название</Label>
                <Input id="title" name="title" required maxlength="255" />
                <InputError :message="errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="description">Краткое описание</Label>
                <Input
                    id="description"
                    name="description"
                    required
                    maxlength="255"
                />
                <InputError :message="errors.description" />
            </div>

            <div class="grid gap-2">
                <Label for="content">Условие / задание</Label>
                <textarea
                    id="content"
                    name="content"
                    required
                    rows="8"
                    class="w-full border border-border bg-background p-2 text-sm"
                />
                <InputError :message="errors.content" />
            </div>

            <div class="grid gap-2">
                <Label for="answer">Эталонный ответ</Label>
                <p class="text-muted-foreground text-xs">
                    Не показывается пользователям. Для проверки решений позже.
                </p>
                <textarea
                    id="answer"
                    name="answer"
                    required
                    rows="4"
                    class="w-full border border-border bg-background p-2 font-mono text-sm"
                />
                <InputError :message="errors.answer" />
            </div>

            <div class="flex gap-4">
                <Button type="submit" :disabled="processing">
                    Опубликовать
                </Button>
                <Link :href="trainersIndex()" class="py-2 text-sm underline">
                    Отмена
                </Link>
            </div>
        </Form>
    </div>
</template>