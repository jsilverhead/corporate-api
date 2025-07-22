<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Survey;

readonly class CreateSurveyTemplateDto
{
    /**
     * @psalm-param non-empty-string $name
     * @psalm-param list<non-empty-string> $questions
     */
    public function __construct(public string $name, public array $questions)
    {
    }
}
