<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Survey;

use App\Domain\Survey\Service\DeleteSurveyTemplateService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\SurveyTemplateBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(DeleteSurveyTemplateService::class)]
final class DeleteSurveyTemplateServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $template = $this->getService(SurveyTemplateBuilder::class)->build();

        $this->getService(DeleteSurveyTemplateService::class)->delete($template);

        self::assertInstanceOf(expected: DateTimeImmutable::class, actual: $template->deletedAt);
    }
}
