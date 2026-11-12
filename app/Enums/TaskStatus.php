<?php

namespace App\Enums;

enum TaskStatus: string
{
    case CREATED = 'CREATED';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
}
