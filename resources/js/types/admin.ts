import type { AdminTrainerApplicationListItem } from '@/types/trainer-application';

export type AdminTab = 'news' | 'trainers' | 'applications';

export type AdminNewsListItem = {
    id: string;
    title: string;
    author: string | null;
    created_at: string | null;
};

export type AdminTrainerListItem = {
    id: string;
    title: string;
    description: string;
    created_at: string | null;
};

export type { AdminTrainerApplicationListItem };