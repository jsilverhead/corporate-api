<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Survey;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Survey\Repository\SurveyRepository;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Survey\ListSurveysDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Survey\ListSurveysNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/listSurveys', methods: [Request::METHOD_GET])]
readonly class ListSurveys
{
    public function __construct(
        private Responder $responder,
        private SurveyRepository $surveyRepository,
        private ListSurveysDenormalizer $listSurveysDenormalizer,
        private ListSurveysNormalizer $listSurveysNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $dto = $this->listSurveysDenormalizer->denormalize($payload);

        $surveys = $this->surveyRepository->listSurveys(
            count: $dto->pagination->count,
            offset: $dto->pagination->offset,
            status: $dto->status,
        );

        $normalizedData = $this->listSurveysNormalizer->normalize($surveys);

        return $this->responder->success($normalizedData);
    }
}
