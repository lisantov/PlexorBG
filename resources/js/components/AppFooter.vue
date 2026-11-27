<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';
</script>

<template>
    <footer
        class="border-t border-border/80 bg-card/40 px-6 py-14 text-sm text-muted-foreground lg:px-10"
    >
        <div
            class="mx-auto flex w-full max-w-[90rem] flex-col gap-8 sm:flex-row sm:items-start sm:justify-between"
        >
            <div class="space-y-2">
                <Link
                    :href="home()"
                    class="text-foreground font-semibold tracking-tight"
                >
                    PlexorBG
                </Link>
                <p class="max-w-xs text-xs leading-relaxed">
                    Колизей программирования — задачи, решения и обсуждения для
                    тех, кто растёт в коде.
                </p>
            </div>
            <div class="flex flex-wrap gap-6 text-xs">
                <a
                    href="https://github.com/lisantov"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="hover:text-primary transition-colors"
                >
                    GitHub
                </a>
                <a
                    href="https://t.me/lisantovv"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="hover:text-primary transition-colors"
                >
                    Telegram
                </a>
                <a
                    href="mailto:lisantov@yandex.ru"
                    class="hover:text-primary transition-colors"
                >
                    lisantov@yandex.ru
                </a>
            </div>
        </div>
        <p class="mx-auto mt-8 w-full max-w-[90rem] text-center text-xs opacity-70">
            © {{ new Date().getFullYear() }} PlexorBG. Все права защищены.
        </p>
    </footer>
</template>