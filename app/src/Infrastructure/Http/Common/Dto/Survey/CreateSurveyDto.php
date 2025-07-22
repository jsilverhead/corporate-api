<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Survey;

use Symfony\Component\Uid\Uuid;

readonly class CreateSurveyDto
{
    public function __construct(public Uuid $employeeId, public Uuid $templateId)
    {
    }
}
