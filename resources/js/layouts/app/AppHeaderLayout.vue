<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppShell from '@/components/AppShell.vue';
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
    <AppShell variant="header">
        <AppHeader />
        <AppContent variant="header" class="min-h-[calc(100vh-3.5rem)]">
            <slot />
        </AppContent>
        <Toaster />
    </AppShell>
</template>