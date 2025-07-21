<?php

declare(strict_types=1);

namespace App\Domain\Survey\Service;

use App\Domain\Survey\SurveyTemplate;
use DateTimeImmutable;

class DeleteSurveyTemplateService
{
    public function delete(SurveyTemplate $template): void
    {
        $template->deletedAt = new DateTimeImmutable();
    }
}
