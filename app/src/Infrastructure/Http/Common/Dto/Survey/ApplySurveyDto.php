<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Survey;

use App\Domain\Survey\Dto\AnswerDataDto;
use Symfony\Component\Uid\Uuid;

readonly class ApplySurveyDto
{
    /**
     * @psalm-param list<AnswerDataDto> $answerData
     */
    public function __construct(public Uuid $surveyId, public array $answerData)
    {
    }
}
