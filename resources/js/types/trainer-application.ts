export type TrainerApplicationStatus = 'CREATED' | 'APPROVED' | 'REJECTED';

export const trainerApplicationStatusLabel: Record<
    TrainerApplicationStatus,
    string
> = {
    CREATED: 'На рассмотрении',
    APPROVED: 'Одобрена',
    REJECTED: 'Отклонена',
};

export type TrainerApplicationListItem = {
    id: string;
    title: string;
    description: string;
    status: TrainerApplicationStatus;
    created_at: string | null;
};

export type AdminTrainerApplicationListItem = {
    id: string;
    title: string;
    description: string;
    author: string | null;
    created_at: string | null;
};