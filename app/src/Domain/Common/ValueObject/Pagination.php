<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

class Pagination
{
    public function __construct(public int $count, public int $offset)
    {
    }
}
