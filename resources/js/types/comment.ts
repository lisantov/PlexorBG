export type CommentNode = {
    id: string;
    content: string;
    author: string | null;
    created_at: string | null;
    children: CommentNode[];
};