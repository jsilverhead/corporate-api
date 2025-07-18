<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Survey;

use App\Domain\Survey\Survey;

class CreateSurveyNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Survey $survey): array
    {
        return [
            'id' => $survey->id->toRfc4122(),
        ];
    }
}
