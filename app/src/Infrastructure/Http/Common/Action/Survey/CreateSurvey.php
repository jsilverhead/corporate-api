<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Survey;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Repository\EmployeeRepository;
use App\Domain\Survey\Repository\SurveyTemplateRepository;
use App\Domain\Survey\Service\CreateSurveyService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Survey\CreateSurveyDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Survey\CreateSurveyNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/createSurvey', methods: [Request::METHOD_POST])]
readonly class CreateSurvey
{
    public function __construct(
        private Responder $responder,
        private CreateSurveyService $createSurveyService,
        private EntityManagerInterface $entityManager,
        private CreateSurveyDenormalizer $createSurveyDenormalizer,
        private EmployeeRepository $employeeRepository,
        private SurveyTemplateRepository $surveyTemplateRepository,
        private CreateSurveyNormalizer $createSurveyNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $dto = $this->createSurveyDenormalizer->denormalize($payload);
        $employee = $this->employeeRepository->getByIdOrFail($dto->employeeId);
        $template = $this->surveyTemplateRepository->getByIdWithoutDeletedOrFail($dto->templateId);

        $survey = $this->createSurveyService->create(employee: $employee, template: $template);

        // TODO: Добавить отправку сообщения о необходимости заполнить анкету

        $this->entityManager->flush();

        $normalizedData = $this->createSurveyNormalizer->normalize($survey);

        return $this->responder->success($normalizedData);
    }
}
