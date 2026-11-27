<script setup lang="ts">
import { Form, Link } from '@inertiajs/vue3';
import { ChevronDown } from '@lucide/vue';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import type { InertiaFormComponentProps } from '@inertiajs/vue3';
import type { LinkComponentBaseProps } from '@inertiajs/core';

export type ListSortOption = {
    value: string;
    label: string;
};

type Props = {
    form: InertiaFormComponentProps;
    search: string;
    sort: string;
    sortOptions: ListSortOption[];
    sortHref: (value: string) => LinkComponentBaseProps['href'];
    resetHref: LinkComponentBaseProps['href'];
};

const props = defineProps<Props>();

const currentSortLabel = computed(
    () =>
        props.sortOptions.find((o) => o.value === props.sort)?.label ??
        'Сортировка',
);
</script>

<template>
    <div class="mb-8 flex flex-wrap items-center gap-3">
        <Form v-bind="form" class="flex min-w-0 flex-1 flex-wrap items-center gap-3">
            <input type="hidden" name="sort" :value="sort" />
            <Input
                type="search"
                name="search"
                placeholder="Поиск по названию"
                class="h-10 min-w-[12rem] flex-1 md:max-w-xl"
                :default-value="search"
            />
            <Button type="submit" variant="secondary" class="h-10 shrink-0 px-5">
                Найти
            </Button>
            <Link
                v-if="search !== ''"
                :href="resetHref"
                class="text-muted-foreground shrink-0 text-sm underline hover:text-foreground"
            >
                Сбросить
            </Link>
        </Form>

        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button variant="outline" class="h-10 shrink-0 gap-2 border-2">
                    {{ currentSortLabel }}
                    <ChevronDown class="size-4 opacity-70" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" class="min-w-44">
                <DropdownMenuItem
                    v-for="option in sortOptions"
                    :key="option.value"
                    as-child
                >
                    <Link
                        :href="sortHref(option.value)"
                        preserve-scroll
                        class="w-full cursor-pointer"
                        :class="
                            sort === option.value
                                ? 'text-primary font-medium'
                                : ''
                        "
                    >
                        {{ option.label }}
                    </Link>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>