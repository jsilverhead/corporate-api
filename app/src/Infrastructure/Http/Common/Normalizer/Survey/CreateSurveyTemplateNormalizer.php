<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Survey;

use App\Domain\Survey\SurveyTemplate;

class CreateSurveyTemplateNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(SurveyTemplate $template): array
    {
        return [
            'id' => $template->id->toRfc4122(),
        ];
    }
}
