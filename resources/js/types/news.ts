import type { LikeMeta } from '@/types/likeable';

export type NewsSort = 'latest' | 'likes';

export type NewsListItem = {
    id: string;
    title: string;
    content: string;
    image_url: string | null;
    author: string | null;
    created_at: string | null;
    comments_count?: number;
} & LikeMeta;

export type NewsDetail = Pick<
    NewsListItem,
    | 'id'
    | 'title'
    | 'content'
    | 'image_url'
    | 'author'
    | 'created_at'
    | 'likes_count'
    | 'liked'
>;

export type NewsAdminEdit = Pick<
    NewsListItem,
    'id' | 'title' | 'content' | 'image_url'
>;