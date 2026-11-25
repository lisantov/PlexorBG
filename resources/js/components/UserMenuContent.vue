<script setup lang="ts">
import { Form, Link } from '@inertiajs/vue3';
import { LogOut, Settings, User } from '@lucide/vue';
import SessionController from '@/actions/App/Http/Controllers/Auth/SessionController';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { edit as settingsEdit, index as profilePage } from '@/routes/profile';
import type { User as AuthUser } from '@/types';

type Props = {
    user: AuthUser;
};

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="px-2 py-1 text-xs text-muted-foreground">
        {{ user.email }}
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link class="cursor-pointer" :href="profilePage()">
                <User class="mr-2 h-4 w-4" />
                Профиль
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <Link class="cursor-pointer" :href="settingsEdit()">
                <Settings class="mr-2 h-4 w-4" />
                Настройки
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true">
        <Form
            v-bind="SessionController.destroy.form()"
            class="w-full"
            v-slot="{ processing }"
        >
            <button
                type="submit"
                class="flex w-full cursor-pointer items-center px-2 py-1.5 text-sm"
                :disabled="processing"
            >
                <LogOut class="mr-2 h-4 w-4" />
                Выход
            </button>
        </Form>
    </DropdownMenuItem>
</template>