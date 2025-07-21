<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Survey;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Survey\Repository\SurveyTemplateRepository;
use App\Domain\Survey\Service\DeleteSurveyTemplateService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Survey\DeleteSurveyTemplateDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/deleteSurveyTemplate', methods: [Request::METHOD_POST])]
readonly class DeleteSurveyTemplate
{
    public function __construct(
        private Responder $responder,
        private DeleteSurveyTemplateDenormalizer $deleteSurveyTemplateDenormalizer,
        private DeleteSurveyTemplateService $deleteSurveyTemplateService,
        private EntityManagerInterface $entityManager,
        private SurveyTemplateRepository $surveyTemplateRepository,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $id = $this->deleteSurveyTemplateDenormalizer->denormalize($payload);
        $template = $this->surveyTemplateRepository->getByIdWithoutDeletedOrFail($id);

        $this->deleteSurveyTemplateService->delete($template);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
