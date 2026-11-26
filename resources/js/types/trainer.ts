import type { LikeMeta } from '@/types/likeable';

export type TrainerListItem = {
    id: string;
    title: string;
    description: string;
    created_at: string | null;
    comments_count?: number;
} & LikeMeta;

export type TrainerDetail = TrainerListItem & {
    content: string;
};

export type TrainerAdminFields = Pick<
    TrainerDetail,
    'id' | 'title' | 'description' | 'content'
> & {
    answer: string;
};