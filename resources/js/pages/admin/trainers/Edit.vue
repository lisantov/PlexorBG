<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import TrainerController from '@/actions/App/Http/Controllers/Admin/TrainerController';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as adminIndex } from '@/routes/admin';
import type { TrainerAdminFields } from '@/types/trainer';

type Props = {
    trainer: TrainerAdminFields;
};

const props = defineProps<Props>();
</script>

<template>
    <Head title="Редактировать тренажёр" />

    <div class="mx-auto max-w-lg px-4 py-10">
        <h1 class="mb-6 text-2xl font-semibold">Редактировать тренажёр</h1>

        <Form
            v-bind="TrainerController.update.form(props.trainer.id)"
            class="mb-8 flex flex-col gap-4"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="title">Название</Label>
                <Input
                    id="title"
                    name="title"
                    required
                    maxlength="255"
                    :default-value="trainer.title"
                />
                <InputError :message="errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="description">Краткое описание</Label>
                <Input
                    id="description"
                    name="description"
                    required
                    maxlength="255"
                    :default-value="trainer.description"
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
                    :default-value="trainer.content"
                />
                <InputError :message="errors.content" />
            </div>

            <div class="grid gap-2">
                <Label for="answer">Эталонный ответ</Label>
                <textarea
                    id="answer"
                    name="answer"
                    required
                    rows="4"
                    class="w-full border border-border bg-background p-2 font-mono text-sm"
                    :default-value="trainer.answer"
                />
                <InputError :message="errors.answer" />
            </div>

            <div class="flex gap-4">
                <Button type="submit" :disabled="processing">Сохранить</Button>
                <Link
                    :href="adminIndex({ query: { tab: 'trainers' } })"
                    class="py-2 text-sm underline"
                >
                    Отмена
                </Link>
            </div>
        </Form>

        <ConfirmDeleteDialog
            :form="TrainerController.destroy.form(props.trainer.id)"
            title="Удалить тренажёр?"
            :description="`«${trainer.title}» будет удалён безвозвратно.`"
        />
    </div>
</template>