import type { LikeMeta } from '@/types/likeable';

export type NewsSort = 'latest' | 'likes';

export type NewsListItem = {
    id: string;
    title: string;
    content: string;
    author: string | null;
    created_at: string | null;
    comments_count?: number;
} & LikeMeta;

export type NewsDetail = Pick<
    NewsListItem,
    | 'id'
    | 'title'
    | 'content'
    | 'author'
    | 'created_at'
    | 'likes_count'
    | 'liked'
>;