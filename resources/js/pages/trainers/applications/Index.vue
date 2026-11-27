<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PageContainer from '@/components/layout/PageContainer.vue';
import PagePanel from '@/components/layout/PagePanel.vue';
import PageTitle from '@/components/layout/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { create as applicationCreate } from '@/routes/trainer-applications';
import {
    trainerApplicationStatusLabel,
    type TrainerApplicationListItem,
} from '@/types/trainer-application';

type Props = {
    applications: TrainerApplicationListItem[];
};

defineProps<Props>();

const formatDate = (value: string | null) =>
    value ? new Date(value).toLocaleString() : '—';

const statusClass = (status: TrainerApplicationListItem['status']) => {
    switch (status) {
        case 'CREATED':
            return 'text-primary';
        case 'APPROVED':
            return 'text-green-600 dark:text-green-400';
        case 'REJECTED':
            return 'text-muted-foreground';
    }
};
</script>

<template>
    <Head title="Мои заявки" />

    <PageContainer>
        <PageTitle
            title="Мои заявки"
            description="Заявки на добавление тренажёров на арену PlexorBG."
        >
            <template #actions>
                <Button as-child>
                    <Link :href="applicationCreate()">Новая заявка</Link>
                </Button>
            </template>
        </PageTitle>

        <PagePanel class="space-y-4">
            <p
                v-if="applications.length === 0"
                class="text-muted-foreground border border-border px-4 py-8 text-center text-sm"
            >
                Вы ещё не отправляли заявок.
                <Link
                    :href="applicationCreate()"
                    class="text-primary ml-1 underline"
                >
                    Создать первую
                </Link>
            </p>

            <ul
                v-else
                class="divide-y divide-border border border-border text-sm"
            >
                <li
                    v-for="item in applications"
                    :key="item.id"
                    class="flex flex-wrap items-center justify-between gap-3 px-4 py-3"
                >
                    <div class="min-w-0 space-y-1">
                        <p class="font-medium">{{ item.title }}</p>
                        <p class="text-muted-foreground text-xs">
                            {{ item.description }}
                        </p>
                        <p
                            class="text-xs"
                            :class="statusClass(item.status)"
                        >
                            {{
                                trainerApplicationStatusLabel[item.status]
                            }}
                            · {{ formatDate(item.created_at) }}
                        </p>
                    </div>
                </li>
            </ul>
        </PagePanel>
    </PageContainer>
</template>