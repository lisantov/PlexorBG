<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Menu } from '@lucide/vue';
import { computed } from 'vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { create as adminNewsCreate } from '@/routes/admin/news';
import { create as adminTrainerCreate } from '@/routes/admin/trainers';
import { home, login, register } from '@/routes';
import { index as news } from '@/routes/news';
import { index as trainers } from '@/routes/trainers';
import type { LinkComponentBaseProps } from '@inertiajs/core';

const page = usePage();
const auth = computed(() => page.props.auth);
const { whenCurrentUrl } = useCurrentUrl();

const navLinkClass = (href: LinkComponentBaseProps['href']) =>
    [
        'text-sm text-muted-foreground transition-colors hover:text-foreground',
        whenCurrentUrl(href, 'text-foreground'),
    ];
</script>

<template>
    <header class="border-b border-border">
        <div
            class="mx-auto flex h-14 max-w-5xl items-center justify-between gap-4 px-4"
        >
            <Link :href="home()" class="text-sm font-semibold tracking-tight">
                {{ page.props.name }}
            </Link>

            <nav class="hidden items-center gap-6 sm:flex">
                <Link :href="home()" :class="navLinkClass(home())">Главная</Link>
                <Link :href="news()" :class="navLinkClass(news())">Новости</Link>
                <Link :href="trainers()" :class="navLinkClass(trainers())">
                    Тренажёры
                </Link>
                <Link
                    v-if="auth.isAdmin"
                    :href="adminNewsCreate()"
                    :class="navLinkClass(adminNewsCreate())"
                >
                    + Новость
                </Link>
                <Link
                    v-if="auth.isAdmin"
                    :href="adminTrainerCreate()"
                    :class="navLinkClass(adminTrainerCreate())"
                >
                    + Тренажёр
                </Link>
            </nav>

            <div class="flex items-center gap-3">
                <template v-if="auth.user">
                    <DropdownMenu>
                        <DropdownMenuTrigger
                            class="rounded-md px-2 py-1 text-sm text-muted-foreground hover:text-foreground"
                        >
                            {{ auth.user.login }}
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-48">
                            <UserMenuContent :user="auth.user" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </template>
                <template v-else>
                    <Link
                        :href="login()"
                        class="text-sm text-muted-foreground hover:text-foreground"
                    >
                        Вход
                    </Link>
                    <Link
                        :href="register()"
                        class="text-sm text-foreground underline underline-offset-4"
                    >
                        Регистрация
                    </Link>
                </template>

                <details class="relative sm:hidden">
                    <summary
                        class="list-none cursor-pointer rounded-md p-2 text-muted-foreground hover:text-foreground"
                    >
                        <Menu class="size-5" />
                    </summary>
                    <div
                        class="absolute right-0 z-50 mt-2 flex w-40 flex-col gap-2 rounded-md border bg-background p-2 text-sm shadow-md"
                    >
                        <Link :href="home()">Главная</Link>
                        <Link :href="news()">Новости</Link>
                        <Link :href="trainers()">Тренажёры</Link>
                        <Link v-if="auth.isAdmin" :href="adminNewsCreate()">
                            + Новость
                        </Link>
                        <Link v-if="auth.isAdmin" :href="adminTrainerCreate()">
                            + Тренажёр
                        </Link>
                    </div>
                </details>
            </div>
        </div>
    </header>
</template>