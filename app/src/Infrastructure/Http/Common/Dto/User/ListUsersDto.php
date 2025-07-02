<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\User;

use App\Domain\Common\ValueObject\Pagination;

class ListUsersDto
{
    /**
     * @psalm-param list<string>|null $searchWords
     */
    public function __construct(public Pagination $pagination, public ?array $searchWords)
    {
    }
}
