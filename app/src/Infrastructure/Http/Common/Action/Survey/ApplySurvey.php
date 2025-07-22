<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Survey;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Survey\Repository\SurveyRepository;
use App\Domain\Survey\Service\ApplySurveyService;
use App\Domain\Survey\Service\QuestionWithAnswerValidatorAndCreator;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Survey\ApplySurveyDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/applySurvey', methods: [Request::METHOD_POST])]
readonly class ApplySurvey
{
    public function __construct(
        private Responder $responder,
        private ApplySurveyService $applySurveyService,
        private EntityManagerInterface $entityManager,
        private ApplySurveyDenormalizer $applySurveyDenormalizer,
        private SurveyRepository $surveyRepository,
        private QuestionWithAnswerValidatorAndCreator $questionWithAnswerValidatorAndCreator,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $dto = $this->applySurveyDenormalizer->denormalize($payload);
        $survey = $this->surveyRepository->getByIdAndApplier(employee: $employee, surveyId: $dto->surveyId);

        $questionsWithAnswers = $this->questionWithAnswerValidatorAndCreator->create(
            answerData: $dto->answerData,
            survey: $survey,
        );

        $this->applySurveyService->apply(survey: $survey, questionsWithAnswer: $questionsWithAnswers);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
