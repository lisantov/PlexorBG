<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col justify-center bg-background px-4">
        <div class="mx-auto w-full max-w-sm space-y-6">
            <Link :href="home()" class="text-sm text-muted-foreground hover:text-foreground">
                ← На главную
            </Link>
            <div v-if="title" class="space-y-1">
                <h1 class="text-xl font-semibold">{{ title }}</h1>
                <p v-if="description" class="text-muted-foreground text-sm">
                    {{ description }}
                </p>
            </div>
            <slot />
        </div>
    </div>
</template>