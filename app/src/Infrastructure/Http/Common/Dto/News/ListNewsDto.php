<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\News;

use App\Domain\Common\ValueObject\Pagination;
use App\Domain\News\Enum\NewsOrderDirectionEnum;

readonly class ListNewsDto
{
    public function __construct(public Pagination $pagination, public NewsOrderDirectionEnum $orderDirection)
    {
    }
}
