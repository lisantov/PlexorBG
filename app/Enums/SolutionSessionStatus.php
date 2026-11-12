<?php

namespace App\Enums;

enum SolutionSessionStatus: string
{
    case IN_PROGRESS = 'IN_PROGRESS';
    case CANCELLED = 'CANCELLED';
    case FINISHED = 'FINISHED';
}
