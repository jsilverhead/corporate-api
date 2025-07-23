<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Vacation;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Vacation\Service\CreateVacationService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Vacation\CreateVacationDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Vacation\CreateVacationNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/createVacation', methods: [Request::METHOD_POST])]
readonly class CreateVacation
{
    public function __construct(
        private Responder $responder,
        private CreateVacationDenormalizer $createVacationDenormalizer,
        private CreateVacationService $createVacationService,
        private CreateVacationNormalizer $createVacationNormalizer,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $period = $this->createVacationDenormalizer->denormalize($payload);

        $vacation = $this->createVacationService->create(employee: $employee, period: $period);

        $normalizedData = $this->createVacationNormalizer->normalize($vacation);

        return $this->responder->success($normalizedData);
    }
}
