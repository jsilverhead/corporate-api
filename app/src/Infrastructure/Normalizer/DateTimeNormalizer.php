<?php

declare(strict_types=1);

namespace App\Infrastructure\Normalizer;

use DateTimeImmutable;

class DateTimeNormalizer
{
    public function normalize(DateTimeImmutable $dateTime): string
    {
        return $dateTime->format('c');
    }
}
