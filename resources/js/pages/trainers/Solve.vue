<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SolveController from '@/actions/App/Http/Controllers/Trainers/SolveController';
import PageContainer from '@/components/layout/PageContainer.vue';
import PagePanel from '@/components/layout/PagePanel.vue';
import { Button } from '@/components/ui/button';
import { show as trainerShow, index as trainersIndex } from '@/routes/trainers';
import type { TrainerDetail } from '@/types/trainer';

type SessionPayload = {
    id: string;
    code: string;
};

type Props = {
    trainer: Pick<TrainerDetail, 'id' | 'title' | 'description' | 'content'>;
    session: SessionPayload;
};

const props = defineProps<Props>();

const code = ref(props.session.code);
const busy = ref(false);

const postWithCode = (url: string, onSuccess?: () => void) => {
    busy.value = true;
    router.post(
        url,
        { code: code.value },
        {
            preserveScroll: true,
            onFinish: () => {
                busy.value = false;
            },
            onSuccess: (page) => {
                const payload = page.props.session as SessionPayload | undefined;
                if (payload) {
                    code.value = payload.code;
                }
                onSuccess?.();
            },
        },
    );
};

const submitSolution = () =>
    postWithCode(SolveController.submit.url(props.trainer.id));
const finishSession = () => {
    busy.value = true;
    router.post(
        SolveController.finish.url(props.trainer.id),
        { code: code.value },
        {
            onFinish: () => {
                busy.value = false;
            },
        },
    );
};
const resetSolution = () => {
    busy.value = true;
    router.post(
        SolveController.reset.url(props.trainer.id),
        {},
        {
            preserveScroll: true,
            onFinish: () => {
                busy.value = false;
            },
            onSuccess: () => {
                code.value = '';
            },
        },
    );
};
</script>

<template>
    <Head :title="`Решение: ${trainer.title}`" />

    <PageContainer>
        <div class="mb-6 flex flex-wrap items-center gap-4 text-sm">
            <Link
                :href="trainersIndex()"
                class="text-muted-foreground hover:text-primary"
            >
                ← Все тренажёры
            </Link>
            <Link
                :href="trainerShow(trainer.id)"
                class="text-muted-foreground hover:text-primary"
            >
                К странице задачи
            </Link>
        </div>

        <div class="grid min-h-[calc(100vh-12rem)] gap-6 lg:grid-cols-2">
            <PagePanel class="flex flex-col gap-6">
                <div class="space-y-3">
                    <h1 class="text-2xl font-bold tracking-tight lg:text-3xl">
                        {{ trainer.title }}
                    </h1>
                    <p class="text-muted-foreground text-sm">
                        {{ trainer.description }}
                    </p>
                </div>

                <section
                    class="max-h-[40vh] flex-1 overflow-y-auto rounded-xl border-2 border-border/80 bg-background/50 p-4 text-sm leading-relaxed whitespace-pre-wrap lg:max-h-none"
                >
                    {{ trainer.content }}
                </section>

                <div class="flex flex-col gap-3 border-t border-border pt-4">
                    <Button
                        type="button"
                        :disabled="busy"
                        @click="submitSolution"
                    >
                        Отправить решение
                    </Button>
                    <Button
                        type="button"
                        variant="secondary"
                        :disabled="busy"
                        @click="finishSession"
                    >
                        Завершить сессию
                    </Button>
                    <Button
                        type="button"
                        variant="outline"
                        :disabled="busy"
                        @click="resetSolution"
                    >
                        Сбросить решение
                    </Button>
                </div>
            </PagePanel>

            <PagePanel class="flex min-h-[28rem] flex-col !p-0 lg:min-h-0">
                <label
                    for="solution-code"
                    class="border-b border-border px-4 py-3 text-sm font-medium"
                >
                    Код решения
                </label>
                <textarea
                    id="solution-code"
                    v-model="code"
                    class="min-h-[28rem] w-full flex-1 resize-none rounded-b-2xl border-0 bg-background p-4 font-mono text-sm leading-relaxed focus:ring-0 focus:outline-none lg:min-h-0"
                    spellcheck="false"
                    placeholder="// ваш код"
                />
            </PagePanel>
        </div>
    </PageContainer>
</template>