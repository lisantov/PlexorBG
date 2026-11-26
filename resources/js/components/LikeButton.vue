<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Heart } from '@lucide/vue';
import type { LikeMeta } from '@/types/likeable';
import type { InertiaFormComponentProps } from '@inertiajs/vue3';

type Props = LikeMeta & {
    canLike: boolean;
    likeForm?: InertiaFormComponentProps;
};

defineProps<Props>();
</script>

<template>
    <Form
        v-if="canLike && likeForm"
        v-bind="likeForm"
        class="inline-flex"
        preserve-scroll
    >
        <button
            type="submit"
            class="text-muted-foreground inline-flex items-center gap-1 text-xs transition-colors hover:text-foreground"
            :class="liked ? 'text-rose-500 hover:text-rose-500' : ''"
            :title="liked ? 'Убрать лайк' : 'Нравится'"
        >
            <Heart class="size-3.5" :class="liked ? 'fill-current' : ''" />
            <span>{{ likes_count }}</span>
        </button>
    </Form>
    <span
        v-else
        class="text-muted-foreground inline-flex items-center gap-1 text-xs"
        title="Лайки"
    >
        <Heart class="size-3.5" />
        <span>{{ likes_count }}</span>
    </span>
</template>