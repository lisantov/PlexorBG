<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import PageContainer from '@/components/layout/PageContainer.vue';
import PagePanel from '@/components/layout/PagePanel.vue';
import PageTitle from '@/components/layout/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { edit as settingsEdit } from '@/routes/profile';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Профиль" />

    <PageContainer narrow>
        <PageTitle
            title="Профиль"
            description="Данные вашего аккаунта на PlexorBG."
        />

        <PagePanel v-if="user" class="space-y-6">
            <dl class="grid gap-4 text-base sm:grid-cols-[8rem_1fr]">
                <dt class="text-muted-foreground">Login</dt>
                <dd class="font-medium">{{ user.login }}</dd>
                <dt class="text-muted-foreground">Email</dt>
                <dd>{{ user.email }}</dd>
            </dl>

            <Button as-child variant="secondary">
                <Link :href="settingsEdit()">Настройки аккаунта</Link>
            </Button>
        </PagePanel>
    </PageContainer>
</template>