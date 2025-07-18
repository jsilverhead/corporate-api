<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Survey;

use App\Domain\Survey\Question;
use App\Domain\Survey\Service\CreateSurveyTemplateService;
use App\Domain\Survey\SurveyTemplate;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateSurveyTemplateService::class)]
final class CreateSurveyTemplateServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $question1 = 'Расскажите о вашем хобби?';
        $question2 = 'Ваше семейное положение?';
        $question3 = 'Есть ли у вас домашние животные? Расскажите о них.';
        $name = 'Для менеджеров';
        $questions = [$question1, $question2, $question3];

        $template = $this->getService(CreateSurveyTemplateService::class)->create(questions: $questions, name: $name);

        self::assertInstanceOf(SurveyTemplate::class, $template);
        self::assertSame(expected: $name, actual: $template->name);

        foreach ($questions as $question) {
            /**
             * @psalm-var Question $currentQuestion
             */
            $currentQuestion = $template->questions->current();

            self::assertSame(expected: $question, actual: $currentQuestion->question);

            $template->questions->next();
        }
    }
}
