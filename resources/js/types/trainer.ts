export type TrainerListItem = {
    id: string;
    title: string;
    description: string;
    created_at: string | null;
};

export type TrainerDetail = TrainerListItem & {
    content: string;
};

export type TrainerAdminFields = Pick<
    TrainerDetail,
    'id' | 'title' | 'description' | 'content'
> & {
    answer: string;
};