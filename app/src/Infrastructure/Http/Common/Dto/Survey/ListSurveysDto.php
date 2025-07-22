<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Survey;

use App\Domain\Common\ValueObject\Pagination;
use App\Domain\Survey\Enum\StatusEnum;

readonly class ListSurveysDto
{
    public function __construct(public Pagination $pagination, public StatusEnum $status)
    {
    }
}
