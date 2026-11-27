<script setup lang="ts">
withDefaults(
    defineProps<{
        narrow?: boolean;
    }>(),
    { narrow: false },
);
</script>

<template>
    <div
        class="mx-auto w-full px-6 py-10 lg:px-10 lg:py-12"
        :class="narrow ? 'max-w-4xl' : 'max-w-[90rem]'"
    >
        <slot />
    </div>
</template>