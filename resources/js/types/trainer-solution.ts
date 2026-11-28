import type { CommentNode } from '@/types/comment';
import type { LikeMeta } from '@/types/likeable';

export type TrainerShowTab = 'task' | 'solutions' | 'comments';

export type TrainerSolutionListItem = {
    id: string;
    author: string | null;
    code: string | null;
    created_at: string | null;
    comments: CommentNode[];
    comments_count: number;
} & LikeMeta;