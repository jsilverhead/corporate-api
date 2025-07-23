<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use DateTimeImmutable;

class Period
{
    public function __construct(public DateTimeImmutable $fromDate, public DateTimeImmutable $toDate)
    {
    }
}
