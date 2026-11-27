<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, Swords } from '@lucide/vue';
import { computed } from 'vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { Button } from '@/components/ui/button';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { index as adminIndex } from '@/routes/admin';
import { home, login, register } from '@/routes';
import { index as news } from '@/routes/news';
import { index as trainers } from '@/routes/trainers';
import type { LinkComponentBaseProps } from '@inertiajs/core';

const page = usePage();
const auth = computed(() => page.props.auth);
const { whenCurrentUrl } = useCurrentUrl();

const navLinkClass = (href: LinkComponentBaseProps['href']) =>
    [
        'text-sm transition-colors',
        whenCurrentUrl(href, 'text-primary font-medium', 'text-muted-foreground hover:text-foreground'),
    ];
</script>

<template>
    <header
        class="sticky top-0 z-40 border-b border-border/70 bg-background/80 backdrop-blur-md"
    >
        <div
            class="mx-auto flex h-16 w-full max-w-[90rem] items-center justify-between gap-4 px-6 lg:px-10"
        >
            <Link
                :href="home()"
                class="group flex items-center gap-2 text-sm font-semibold tracking-tight"
            >
                <span
                    class="bg-primary/15 text-primary flex size-8 items-center justify-center rounded-md border border-primary/30 transition group-hover:bg-primary/25"
                >
                    <Swords class="size-4" />
                </span>
                <span>
                    <span class="text-primary">Plexor</span
                    ><span class="text-foreground">BG</span>
                </span>
            </Link>

            <nav class="hidden items-center gap-8 sm:flex">
                <Link :href="home()" :class="navLinkClass(home())">Главная</Link>
                <Link :href="news()" :class="navLinkClass(news())">Новости</Link>
                <Link :href="trainers()" :class="navLinkClass(trainers())">
                    Тренажёры
                </Link>
                <Link
                    v-if="auth.isAdmin"
                    :href="adminIndex()"
                    :class="navLinkClass(adminIndex())"
                >
                    Админ
                </Link>
            </nav>

            <div class="flex items-center gap-2">
                <template v-if="auth.user">
                    <DropdownMenu>
                        <DropdownMenuTrigger
                            class="rounded-md border border-border/80 px-3 py-1.5 text-sm text-muted-foreground hover:border-primary/40 hover:text-foreground"
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
                        class="text-muted-foreground hidden text-sm hover:text-foreground sm:inline"
                    >
                        Вход
                    </Link>
                    <Button as-child size="sm" class="shadow-sm shadow-primary/20">
                        <Link :href="register()">Присоединиться</Link>
                    </Button>
                </template>

                <details class="relative sm:hidden">
                    <summary
                        class="list-none cursor-pointer rounded-md border border-border p-2 text-muted-foreground"
                    >
                        <Menu class="size-5" />
                    </summary>
                    <div
                        class="absolute right-0 z-50 mt-2 flex w-44 flex-col gap-2 rounded-md border bg-background p-2 text-sm shadow-lg"
                    >
                        <Link :href="home()">Главная</Link>
                        <Link :href="news()">Новости</Link>
                        <Link :href="trainers()">Тренажёры</Link>
                        <Link v-if="auth.isAdmin" :href="adminIndex()">
                            Админ
                        </Link>
                        <template v-if="!auth.user">
                            <Link :href="login()">Вход</Link>
                            <Link :href="register()" class="text-primary">
                                Присоединиться
                            </Link>
                        </template>
                    </div>
                </details>
            </div>
        </div>
    </header>
</template>