<?php

declare(strict_types=1);

namespace App\Domain\Survey\Dto;

use Symfony\Component\Uid\Uuid;

readonly class AnswerDataDto
{
    /**
     * @psalm-param non-empty-string $answer
     */
    public function __construct(public Uuid $questionId, public string $answer)
    {
    }
}
