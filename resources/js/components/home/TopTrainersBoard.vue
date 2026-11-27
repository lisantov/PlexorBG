<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { show } from '@/routes/trainers';
import type { HomeTopTrainer, TopTrainerPeriod } from '@/types/home';

type Props = {
    tops: Record<TopTrainerPeriod, HomeTopTrainer[]>;
};

const props = defineProps<Props>();

const period = ref<TopTrainerPeriod>('week');

const periodLabels: Record<TopTrainerPeriod, string> = {
    day: 'День',
    week: 'Неделя',
    month: 'Месяц',
};

const items = computed(() => props.tops[period.value]);
</script>

<template>
    <div class="space-y-5">
        <nav
            class="inline-flex gap-1.5 rounded-xl border-2 border-border/80 bg-card/60 p-1.5 shadow-md"
        >
            <Button
                v-for="(label, key) in periodLabels"
                :key="key"
                type="button"
                size="default"
                :variant="period === key ? 'default' : 'ghost'"
                class="min-w-20 text-sm"
                @click="period = key as TopTrainerPeriod"
            >
                {{ label }}
            </Button>
        </nav>

        <ul
            v-if="items.length"
            class="divide-y divide-border/60 overflow-hidden rounded-2xl border-2 border-border/80 bg-card/50 shadow-lg"
        >
            <li
                v-for="(item, index) in items"
                :key="item.id"
                class="flex items-start gap-4 px-6 py-5 text-base"
            >
                <span
                    class="bg-primary/15 text-primary flex size-10 shrink-0 items-center justify-center rounded-lg font-mono text-sm font-bold"
                >
                    {{ index + 1 }}
                </span>
                <div class="min-w-0 flex-1">
                    <Link
                        :href="show(item.id)"
                        class="hover:text-primary font-medium transition-colors"
                    >
                        {{ item.title }}
                    </Link>
                    <p class="text-muted-foreground mt-1 line-clamp-2 text-sm">
                        {{ item.description }}
                    </p>
                </div>
                <span class="text-muted-foreground shrink-0 text-sm tabular-nums">
                    ♥ {{ item.likes_count }}
                </span>
            </li>
        </ul>
        <p v-else class="text-muted-foreground text-sm">
            За этот период лайков пока нет — будь первым.
        </p>
    </div>
</template>