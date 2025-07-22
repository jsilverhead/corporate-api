<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Survey;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Survey\Repository\SurveyRepository;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Survey\GetSurveyDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Survey\GetSurveyNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/getSurvey', methods: [Request::METHOD_GET])]
readonly class GetSurvey
{
    public function __construct(
        private Responder $responder,
        private GetSurveyDenormalizer $getSurveyDenormalizer,
        private SurveyRepository $surveyRepository,
        private GetSurveyNormalizer $getSurveyNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $id = $this->getSurveyDenormalizer->denormalize($payload);

        $survey = $this->surveyRepository->getByIdWithQuestionsAndAnswersOrFail($id);

        $normalizedData = $this->getSurveyNormalizer->normalize($survey);

        return $this->responder->success($normalizedData);
    }
}
