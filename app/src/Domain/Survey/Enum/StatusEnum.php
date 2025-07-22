<?php

declare(strict_types=1);

namespace App\Domain\Survey\Enum;

enum StatusEnum: string
{
    case ALL = 'all';
    case COMPLETED = 'completed';
    case INCOMPLETE = 'incomplete';
}
