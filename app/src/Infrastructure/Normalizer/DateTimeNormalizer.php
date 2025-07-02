<?php

declare(strict_types=1);

namespace App\Infrastructure\Normalizer;

use DateTimeInterface;

class DateTimeNormalizer
{
    public function normalize(?DateTimeInterface $dateTime): ?string
    {
        return $dateTime?->format(DateTimeInterface::RFC3339);
    }
}
