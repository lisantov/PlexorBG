<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppFooter from '@/components/AppFooter.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppShell from '@/components/AppShell.vue';
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
    <AppShell variant="header">
        <AppHeader />
        <AppContent variant="header" class="min-h-[calc(100vh-4rem)]">
            <slot />
        </AppContent>
        <AppFooter />
        <Toaster />
    </AppShell>
</template>