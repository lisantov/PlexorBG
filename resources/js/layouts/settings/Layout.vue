<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavItem } from '@/types';

const items: NavItem[] = [
    { title: 'Профиль', href: editProfile() },
    { title: 'Безопасность', href: editSecurity() },
    { title: 'Оформление', href: editAppearance() },
];

const { isCurrentOrParentUrl } = useCurrentUrl();
</script>

<template>
    <div class="mx-auto max-w-5xl px-4 py-8">
        <h1 class="mb-6 text-xl font-semibold">Аккаунт</h1>
        <nav class="mb-8 flex flex-wrap gap-4 border-b border-border pb-4 text-sm">
            <Link
                v-for="item in items"
                :key="toUrl(item.href)"
                :href="item.href"
                class="text-muted-foreground hover:text-foreground"
                :class="{
                    'text-foreground underline underline-offset-4':
                        isCurrentOrParentUrl(item.href),
                }"
            >
                {{ item.title }}
            </Link>
        </nav>
        <div class="max-w-xl">
            <slot />
        </div>
    </div>
</template>