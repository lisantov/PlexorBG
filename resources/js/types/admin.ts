export type AdminTab = 'news' | 'trainers';

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
    status: string;
    created_at: string | null;
};