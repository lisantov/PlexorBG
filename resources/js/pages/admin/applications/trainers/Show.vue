<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import TrainerApplicationController from '@/actions/App/Http/Controllers/Admin/TrainerApplicationController';
import InputError from '@/components/InputError.vue';
import PageContainer from '@/components/layout/PageContainer.vue';
import PagePanel from '@/components/layout/PagePanel.vue';
import PageTitle from '@/components/layout/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { index as adminIndex } from '@/routes/admin';

type Application = {
    id: string;
    title: string;
    description: string;
    content: string;
    reference_code: string;
    answer: string;
    author_login: string | null;
    author_email: string | null;
    created_at: string | null;
};

type Props = {
    application: Application;
};

defineProps<Props>();

const formatDate = (value: string | null) =>
    value ? new Date(value).toLocaleString() : '—';
</script>

<template>
    <Head :title="`Заявка: ${application.title}`" />

    <PageContainer narrow>
        <PageTitle
            :title="application.title"
            description="Просмотр заявки на тренажёр."
        />

        <PagePanel class="mb-6 space-y-4 text-sm">
            <dl class="grid gap-3 sm:grid-cols-[8rem_1fr]">
                <dt class="text-muted-foreground">Автор</dt>
                <dd>
                    {{ application.author_login ?? '—' }}
                    <span
                        v-if="application.author_email"
                        class="text-muted-foreground"
                    >
                        ({{ application.author_email }})
                    </span>
                </dd>
                <dt class="text-muted-foreground">Дата</dt>
                <dd>{{ formatDate(application.created_at) }}</dd>
                <dt class="text-muted-foreground">Описание</dt>
                <dd>{{ application.description }}</dd>
            </dl>

            <div>
                <p class="text-muted-foreground mb-2 text-xs uppercase tracking-wide">
                    Условие
                </p>
                <pre
                    class="whitespace-pre-wrap rounded-md border border-border bg-muted/30 p-4 font-sans text-sm"
                    >{{ application.content }}</pre
                >
            </div>

            <div>
                <p
                    class="text-muted-foreground mb-2 text-xs uppercase tracking-wide"
                >
                    Эталонное решение (код)
                </p>
                <pre
                    class="overflow-x-auto rounded-md border border-border bg-muted/30 p-4 font-mono text-sm"
                    >{{ application.reference_code }}</pre
                >
            </div>

            <div>
                <p
                    class="text-muted-foreground mb-2 text-xs uppercase tracking-wide"
                >
                    Эталонный ответ
                </p>
                <pre
                    class="whitespace-pre-wrap rounded-md border border-border bg-muted/30 p-4 font-mono text-sm"
                    >{{ application.answer }}</pre
                >
            </div>
        </PagePanel>

        <PagePanel class="space-y-6">
            <Form
                v-bind="
                    TrainerApplicationController.approve.form(application.id)
                "
                class="border-b border-border pb-6"
                v-slot="{ processing }"
            >
                <Button type="submit" :disabled="processing">
                    Одобрить и опубликовать
                </Button>
                <p class="text-muted-foreground mt-2 text-xs">
                    Публикуются эталонный код и ответ из заявки без изменений.
                </p>
            </Form>

            <details class="group rounded-md border border-border text-sm">
                <summary
                    class="cursor-pointer list-none px-4 py-3 font-medium marker:content-none [&::-webkit-details-marker]:hidden"
                >
                    Изменить эталоны перед публикацией
                    <span class="text-muted-foreground ml-2 text-xs font-normal"
                        >необязательно</span
                    >
                </summary>
                <Form
                    v-bind="
                        TrainerApplicationController.approve.form(
                            application.id,
                        )
                    "
                    class="space-y-4 border-t border-border px-4 py-4"
                    v-slot="{ errors, processing }"
                >
                    <div class="grid gap-2">
                        <Label for="reference_code"
                            >Эталонное решение (код)</Label
                        >
                        <textarea
                            id="reference_code"
                            name="reference_code"
                            rows="10"
                            class="w-full rounded-md border border-border bg-background p-2 font-mono text-sm"
                            >{{ application.reference_code }}</textarea
                        >
                        <InputError :message="errors.reference_code" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="answer">Эталонный ответ</Label>
                        <textarea
                            id="answer"
                            name="answer"
                            rows="4"
                            class="w-full rounded-md border border-border bg-background p-2 font-mono text-sm"
                            >{{ application.answer }}</textarea
                        >
                        <InputError :message="errors.answer" />
                    </div>

                    <Button type="submit" variant="secondary" :disabled="processing">
                        Одобрить с правками
                    </Button>
                </Form>
            </details>

            <Form
                v-bind="
                    TrainerApplicationController.reject.form(application.id)
                "
                v-slot="{ processing }"
            >
                <Button
                    type="submit"
                    variant="destructive"
                    :disabled="processing"
                >
                    Отклонить заявку
                </Button>
            </Form>

            <Button as-child variant="outline">
                <Link :href="adminIndex({ query: { tab: 'applications' } })">
                    Назад к заявкам
                </Link>
            </Button>
        </PagePanel>
    </PageContainer>
</template>