<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import type { InertiaFormComponentProps } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

type Props = {
    form: InertiaFormComponentProps;
    title: string;
    description?: string;
    triggerLabel?: string;
    confirmLabel?: string;
    size?: 'default' | 'sm' | 'lg' | 'icon';
};

withDefaults(defineProps<Props>(), {
    description: 'Это действие нельзя отменить.',
    triggerLabel: 'Удалить',
    confirmLabel: 'Удалить',
    size: 'default',
});

const open = ref(false);
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button variant="destructive" :size="size">
                {{ triggerLabel }}
            </Button>
        </DialogTrigger>
        <DialogContent>
            <Form
                v-bind="form"
                v-slot="{ processing }"
                @success="open = false"
            >
                <DialogHeader>
                    <DialogTitle>{{ title }}</DialogTitle>
                    <DialogDescription>{{ description }}</DialogDescription>
                </DialogHeader>
                <DialogFooter class="gap-2 sm:justify-end">
                    <DialogClose as-child>
                        <Button type="button" variant="outline">
                            Отмена
                        </Button>
                    </DialogClose>
                    <Button
                        type="submit"
                        variant="destructive"
                        :disabled="processing"
                    >
                        {{ confirmLabel }}
                    </Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>