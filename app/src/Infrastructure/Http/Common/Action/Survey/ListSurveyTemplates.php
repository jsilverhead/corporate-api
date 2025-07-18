<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Survey;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Survey\Repository\SurveyTemplateRepository;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Survey\ListSurveyTemplatesDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Survey\ListSurveyTemplatesNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/listSurveyTemplates', methods: [Request::METHOD_GET])]
class ListSurveyTemplates
{
    public function __construct(
        private Responder $responder,
        private SurveyTemplateRepository $surveyTemplateRepository,
        private ListSurveyTemplatesDenormalizer $listSurveyTemplatesDenormalizer,
        private ListSurveyTemplatesNormalizer $listSurveyTemplatesNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $pagination = $this->listSurveyTemplatesDenormalizer->denormalize($payload);

        $templates = $this->surveyTemplateRepository->listSurveyTemplates(
            count: $pagination->count,
            offset: $pagination->offset,
        );

        $normalizedData = $this->listSurveyTemplatesNormalizer->normalize($templates);

        return $this->responder->success($normalizedData);
    }
}
