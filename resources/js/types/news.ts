export type NewsListItem = {
    id: string;
    title: string;
    content: string;
    author: string | null;
    created_at: string | null;
    comments_count?: number;
};

export type NewsDetail = Pick<
    NewsListItem,
    'id' | 'title' | 'content' | 'author' | 'created_at'
>;