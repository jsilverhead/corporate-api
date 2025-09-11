<?php

declare(strict_types=1);

namespace App\Domain\News\Enum;

enum NewsOrderDirectionEnum: string
{
    case ASC = 'asc';
    case DESC = 'desc';
}
