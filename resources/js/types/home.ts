export type HomeNewsCard = {
    id: string;
    title: string;
    image_url: string | null;
    created_at: string | null;
};

export type HomeTopTrainer = {
    id: string;
    title: string;
    description: string;
    likes_count: number;
};

export type TopTrainerPeriod = 'day' | 'week' | 'month';

export type HomeStats = {
    users: string;
    trainers: string;
    solutions: string;
    discussions: string;
};