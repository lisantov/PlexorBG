<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Swords, Trophy, Users, MessageSquare, Sparkles } from '@lucide/vue';
import NewsHighlightCard from '@/components/home/NewsHighlightCard.vue';
import TopTrainersBoard from '@/components/home/TopTrainersBoard.vue';
import { Button } from '@/components/ui/button';
import { register } from '@/routes';
import { index as newsIndex } from '@/routes/news';
import { index as trainersIndex } from '@/routes/trainers';
import type {
    HomeNewsCard,
    HomeStats,
    HomeTopTrainer,
    TopTrainerPeriod,
} from '@/types/home';

type Props = {
    latest_news: HomeNewsCard[];
    top_trainers: Record<TopTrainerPeriod, HomeTopTrainer[]>;
    stats: HomeStats;
};

defineProps<Props>();

const features = [
    {
        icon: Swords,
        title: 'Арена задач',
        text: 'Создавай тренажёры, решай чужие и прокачивай навыки в бою с реальными условиями.',
    },
    {
        icon: Trophy,
        title: 'Рейтинг и прогресс',
        text: 'Лайки, топы и публичные решения — видно, кто двигается вперёд в сообществе.',
    },
    {
        icon: MessageSquare,
        title: 'Обсуждения',
        text: 'Комментарии под новостями и тренажёрами — делись опытом, задавай вопросы.',
    },
    {
        icon: Sparkles,
        title: 'Для авторов',
        text: 'Публикуй задачи, собирай обратную связь и улучшай контент вместе с участниками.',
    },
];

const containerClass = 'mx-auto w-full max-w-[90rem] px-6 lg:px-10';
</script>

<template>
    <Head title="PlexorBG — колизей программирования" />

    <section
        class="relative overflow-hidden border-b border-border/60 px-6 py-20 lg:px-10 lg:py-28"
    >
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,hsl(38_92%_50%/0.15),transparent_55%),radial-gradient(ellipse_at_bottom_right,hsl(220_60%_40%/0.2),transparent_50%)]"
        />
        <div
            :class="[
                containerClass,
                'relative flex flex-col gap-12 lg:flex-row lg:items-center lg:justify-between',
            ]"
        >
            <div class="max-w-3xl space-y-6">
                <p
                    class="text-primary text-sm font-semibold tracking-[0.25em] uppercase"
                >
                    Колизей программирования
                </p>
                <h1
                    class="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl"
                >
                    PlexorBG
                </h1>
                <p
                    class="text-muted-foreground max-w-2xl text-lg leading-relaxed"
                >
                    Платформа в духе CodeWars: создавай задачи, публикуй
                    решения, спорь об идеях на форуме и расти в программировании
                    вместе с сообществом.
                </p>
                <div class="flex flex-wrap gap-4 pt-2">
                    <Button
                        as-child
                        size="lg"
                        class="h-12 px-8 text-base shadow-lg shadow-primary/25"
                    >
                        <Link :href="register()">Присоединиться</Link>
                    </Button>
                    <Button
                        as-child
                        size="lg"
                        variant="outline"
                        class="h-12 border-2 px-8 text-base"
                    >
                        <Link :href="trainersIndex()">Начать решать задачи</Link>
                    </Button>
                </div>
            </div>
            <dl
                class="grid w-full max-w-xl grid-cols-2 gap-4 sm:grid-cols-4 lg:max-w-lg lg:grid-cols-2"
            >
                <div
                    v-for="(value, key) in {
                        Участники: stats.users,
                        Тренажёры: stats.trainers,
                        Решения: stats.solutions,
                        Обсуждения: stats.discussions,
                    }"
                    :key="key"
                    class="rounded-xl border-2 border-border/80 bg-card/80 px-5 py-4 shadow-md backdrop-blur-sm"
                >
                    <dt class="text-muted-foreground text-sm">{{ key }}</dt>
                    <dd
                        class="text-primary mt-2 text-2xl font-bold tabular-nums"
                    >
                        {{ value }}
                    </dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="px-6 py-16 lg:px-10 lg:py-20">
        <div
            :class="[
                containerClass,
                'rounded-3xl border-2 border-border/80 bg-card/40 p-8 shadow-xl shadow-black/20 lg:p-12',
            ]"
        >
            <div
                class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
            >
                <div class="max-w-2xl space-y-2">
                    <h2 class="text-3xl font-bold tracking-tight lg:text-4xl">
                        Актуальные новости
                    </h2>
                    <p class="text-muted-foreground text-base leading-relaxed">
                        Три последние публикации с платформы — главное событие
                        крупно, остальное рядом.
                    </p>
                </div>
                <Button as-child variant="secondary" size="lg" class="shrink-0">
                    <Link :href="newsIndex()">Все новости</Link>
                </Button>
            </div>

            <div
                v-if="latest_news.length"
                class="grid gap-5 md:grid-cols-2 xl:grid-cols-3"
            >
                <NewsHighlightCard
                    v-for="item in latest_news"
                    :key="item.id"
                    :item="item"
                />
            </div>
            <p v-else class="text-muted-foreground text-base">
                Новостей пока нет — скоро появятся анонсы с арены.
            </p>
        </div>
    </section>

    <section
        class="border-y border-border/60 bg-card/25 px-6 py-16 lg:px-10 lg:py-20"
    >
        <div
            :class="[
                containerClass,
                'grid gap-12 lg:grid-cols-2 lg:items-start lg:gap-16',
            ]"
        >
            <div class="space-y-3 lg:max-w-lg">
                <h2 class="text-3xl font-bold tracking-tight">Топ тренажёров</h2>
                <p class="text-muted-foreground text-base leading-relaxed">
                    По лайкам за выбранный период — только опубликованные
                    тренажёры.
                </p>
            </div>
            <TopTrainersBoard :tops="top_trainers" />
        </div>
    </section>

    <section class="px-6 py-16 lg:px-10 lg:py-20">
        <div :class="containerClass">
            <h2 class="text-3xl font-bold tracking-tight lg:text-4xl">
                Как устроен PlexorBG
            </h2>
            <p class="text-muted-foreground mt-3 mb-10 max-w-2xl text-base">
                Черновик описания — уточним вместе по мере развития платформы.
            </p>
            <div class="grid gap-5 md:grid-cols-2">
                <article
                    v-for="feature in features"
                    :key="feature.title"
                    class="rounded-2xl border-2 border-border/80 bg-card/50 p-7 shadow-md transition hover:border-primary/40 hover:shadow-lg"
                >
                    <component
                        :is="feature.icon"
                        class="text-primary mb-4 size-10"
                    />
                    <h3 class="text-lg font-semibold">{{ feature.title }}</h3>
                    <p class="text-muted-foreground mt-3 text-base leading-relaxed">
                        {{ feature.text }}
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section class="border-t border-border/60 px-6 py-20 lg:px-10 lg:py-24">
        <div
            :class="[
                containerClass,
                'flex flex-col items-center gap-6 rounded-3xl border-2 border-border/70 bg-card/30 px-8 py-16 text-center shadow-lg',
            ]"
        >
            <Users class="text-primary size-12" />
            <h2 class="text-3xl font-bold lg:text-4xl">Выйди на арену</h2>
            <p class="text-muted-foreground max-w-2xl text-lg leading-relaxed">
                Зарегистрируйся, выбери тренажёр и оставь первое решение.
                Обсуждай подходы в комментариях — так и строится колизей
                программирования.
            </p>
            <div class="flex flex-wrap justify-center gap-4 pt-2">
                <Button as-child size="lg" class="h-12 px-8 text-base">
                    <Link :href="register()">Присоединиться</Link>
                </Button>
                <Button as-child size="lg" variant="secondary" class="h-12 px-8">
                    <Link :href="trainersIndex()">К тренажёрам</Link>
                </Button>
            </div>
        </div>
    </section>
</template>