<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { show } from '@/routes/news';
import type { HomeNewsCard } from '@/types/home';

type Props = {
    item: HomeNewsCard;
};

defineProps<Props>();

const formatDate = (value: string | null) =>
    value
        ? new Date(value).toLocaleDateString('ru-RU', {
              day: 'numeric',
              month: 'long',
          })
        : '';
</script>

<template>
    <Link
        :href="show(item.id)"
        class="group relative block h-full min-h-72 w-full overflow-hidden rounded-2xl border-2 border-border/80 bg-card shadow-lg shadow-black/25 transition hover:border-primary/50 hover:shadow-primary/10 hover:shadow-xl lg:min-h-80"
    >
        <img
            v-if="item.image_url"
            :src="item.image_url"
            :alt="item.title"
            class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-105"
        />
        <div
            v-else
            class="from-secondary via-muted/80 to-card absolute inset-0 bg-gradient-to-br"
        />

        <div
            class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/45 to-black/15 transition duration-300 group-hover:from-black/95 group-hover:via-black/55"
        />

        <div
            class="absolute inset-0 flex flex-col justify-end p-6 transition duration-300 group-hover:bg-primary/5 md:p-7"
        >
            <p class="text-sm font-medium text-white/75">
                {{ formatDate(item.created_at) }}
            </p>
            <h3
                class="group-hover:text-primary mt-2 line-clamp-3 text-xl font-semibold text-white transition-colors md:text-2xl"
            >
                {{ item.title }}
            </h3>
            <p
                class="mt-3 text-sm font-medium text-white/90 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
            >
                Читать новость →
            </p>
        </div>
    </Link>
</template>