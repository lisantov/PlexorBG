<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import TrainerApplicationController from '@/actions/App/Http/Controllers/TrainerApplicationController';
import InputError from '@/components/InputError.vue';
import PageContainer from '@/components/layout/PageContainer.vue';
import PagePanel from '@/components/layout/PagePanel.vue';
import PageTitle from '@/components/layout/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as applicationsIndex } from '@/routes/trainer-applications';
</script>

<template>
    <Head title="Заявка на тренажёр" />

    <PageContainer narrow>
        <PageTitle
            title="Заявка на тренажёр"
            description="Опишите задачу — модератор проверит заявку и решит, публиковать ли тренажёр на арене."
        />

        <PagePanel>
            <Form
                v-bind="TrainerApplicationController.store.form()"
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
                        rows="10"
                        class="w-full rounded-md border border-border bg-background p-3 text-sm"
                    />
                    <InputError :message="errors.content" />
                </div>

                <div class="grid gap-2">
                    <Label for="reference_code">Эталонное решение (код)</Label>
                    <p class="text-muted-foreground text-xs">
                        Рабочее решение задачи. Видно только модератору до
                        публикации.
                    </p>
                    <textarea
                        id="reference_code"
                        name="reference_code"
                        required
                        rows="10"
                        class="w-full rounded-md border border-border bg-background p-3 font-mono text-sm"
                    />
                    <InputError :message="errors.reference_code" />
                </div>

                <div class="grid gap-2">
                    <Label for="answer">Эталонный ответ</Label>
                    <p class="text-muted-foreground text-xs">
                        Ожидаемый вывод или результат проверки (текст).
                    </p>
                    <textarea
                        id="answer"
                        name="answer"
                        required
                        rows="4"
                        class="w-full rounded-md border border-border bg-background p-3 font-mono text-sm"
                    />
                    <InputError :message="errors.answer" />
                </div>

                <div class="flex flex-wrap gap-3">
                    <Button type="submit" :disabled="processing">
                        Отправить заявку
                    </Button>
                    <Button as-child variant="outline">
                        <Link :href="applicationsIndex()">Отмена</Link>
                    </Button>
                </div>
            </Form>
        </PagePanel>
    </PageContainer>
</template>