<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Swords } from '@lucide/vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative flex min-h-svh flex-col justify-center px-6 py-12 lg:px-10"
    >
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,hsl(38_92%_50%/0.12),transparent_55%)]"
        />

        <div class="relative mx-auto w-full max-w-md space-y-8">
            <Link
                :href="home()"
                class="group flex items-center gap-3 text-sm font-semibold"
            >
                <span
                    class="bg-primary/15 text-primary flex size-10 items-center justify-center rounded-lg border border-primary/30"
                >
                    <Swords class="size-5" />
                </span>
                <span>
                    <span class="text-primary">Plexor</span
                    ><span class="text-foreground">BG</span>
                </span>
            </Link>

            <div
                class="rounded-2xl border-2 border-border/80 bg-card/60 p-8 shadow-xl shadow-black/20 backdrop-blur-sm"
            >
                <div v-if="title" class="mb-6 space-y-2">
                    <h1 class="text-2xl font-bold tracking-tight">{{ title }}</h1>
                    <p v-if="description" class="text-muted-foreground text-sm">
                        {{ description }}
                    </p>
                </div>
                <slot />
            </div>

            <Link
                :href="home()"
                class="text-muted-foreground block text-center text-sm hover:text-primary"
            >
                ← На главную
            </Link>
        </div>
    </div>
</template>