<script setup lang="ts">
defineProps<{
    title: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
    >
        <div>
            <h1 class="text-3xl font-bold tracking-tight lg:text-4xl">
                {{ title }}
            </h1>
            <p
                v-if="description"
                class="text-muted-foreground mt-2 max-w-2xl text-base leading-relaxed"
            >
                {{ description }}
            </p>
        </div>
        <div v-if="$slots.actions" class="flex shrink-0 flex-wrap gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>