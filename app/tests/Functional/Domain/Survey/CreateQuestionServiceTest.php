<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Survey;

use App\Domain\Survey\Question;
use App\Domain\Survey\Service\CreateQuestionService;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateQuestionService::class)]
final class CreateQuestionServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $stringQuestion = 'Ваши лучшие внутренние качества?';
        $question = $this->getService(CreateQuestionService::class)->create(question: $stringQuestion);

        self::assertInstanceOf(Question::class, $question);
        self::assertSame(expected: $stringQuestion, actual: $question->question);
    }
}
