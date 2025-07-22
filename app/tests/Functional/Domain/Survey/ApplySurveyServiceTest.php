<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Survey;

use App\Domain\Survey\Dto\QuestionWithAnswerDto;
use App\Domain\Survey\Exception\AnswersCountIsNotEqualQuestionsCountException;
use App\Domain\Survey\Question;
use App\Domain\Survey\Service\ApplySurveyService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\SurveyBuilder;
use App\Tests\Builder\SurveyTemplateBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ApplySurveyService::class)]
final class ApplySurveyServiceTest extends BaseWebTestCase
{
    public function testLessAnswersThanQuestionsFail(): void
    {
        $question1 = 'Есть ли у вас домашнее животное? Расскажите о нём.';
        $question2 = 'Какие у вас хобби?';
        $template = $this->getService(SurveyTemplateBuilder::class)
            ->withQuestions([$question1, $question2])
            ->build();
        $survey = $this->getService(SurveyBuilder::class)->build(surveyTemplate: $template);
        /**
         * @psalm-var Question $question
         */
        $question = $survey->template->questions->first();
        $answer = 'Хоббихорсинг';
        $answerData = new QuestionWithAnswerDto(question: $question, answer: $answer);

        $this->expectException(AnswersCountIsNotEqualQuestionsCountException::class);
        $this->getService(ApplySurveyService::class)->apply(survey: $survey, questionsWithAnswer: [$answerData]);
    }

    public function testSuccess(): void
    {
        $survey = $this->getService(SurveyBuilder::class)->build();
        /**
         * @psalm-var Question $question
         */
        $question = $survey->template->questions->first();
        $answer = 'Хоббихорсинг';
        $answerData = new QuestionWithAnswerDto(question: $question, answer: $answer);

        $this->getService(ApplySurveyService::class)->apply(survey: $survey, questionsWithAnswer: [$answerData]);

        self::assertTrue(1 === $survey->answers->count());
        self::assertTrue($survey->isCompleted);
    }
}
