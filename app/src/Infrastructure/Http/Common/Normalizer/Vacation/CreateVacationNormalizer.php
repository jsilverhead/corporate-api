<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Vacation;

use App\Domain\Vacation\Vacation;

class CreateVacationNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Vacation $vacation): array
    {
        return [
            'id' => $vacation->id->toRfc4122(),
        ];
    }
}
