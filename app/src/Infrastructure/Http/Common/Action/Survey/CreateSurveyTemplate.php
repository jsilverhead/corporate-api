<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Survey;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Survey\Service\CreateSurveyTemplateService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Survey\CreateSurveyTemplateDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Survey\CreateSurveyTemplateNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/createSurveyTemplate', methods: [Request::METHOD_POST])]
readonly class CreateSurveyTemplate
{
    public function __construct(
        private Responder $responder,
        private CreateSurveyTemplateService $createSurveyTemplateService,
        private EntityManagerInterface $entityManager,
        private CreateSurveyTemplateDenormalizer $createSurveyTemplateDenormalizer,
        private CreateSurveyTemplateNormalizer $createSurveyTemplateNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $questions = $this->createSurveyTemplateDenormalizer->denormalize($payload);

        $template = $this->createSurveyTemplateService->create($questions);

        $this->entityManager->flush();

        $normalizedData = $this->createSurveyTemplateNormalizer->normalize($template);

        return $this->responder->success($normalizedData);
    }
}
