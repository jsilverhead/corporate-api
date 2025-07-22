<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Survey;

use App\Domain\Survey\Survey;
use App\Domain\Survey\SurveyAnswer;

class GetSurveyNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Survey $survey): array
    {
        $answers = null;

        if (0 !== $survey->answers->count()) {
            $answers = array_map(
                static fn(SurveyAnswer $answer) => [
                    'answerId' => $answer->id->toRfc4122(),
                    'questions' => $answer->question->question,
                    'answer' => $answer->answer,
                ],
                (array) $survey->answers->getIterator(),
            );
        }

        return [
            'id' => $survey->id->toRfc4122(),
            'isCompleted' => $survey->isCompleted,
            'answers' => $answers,
        ];
    }
}
