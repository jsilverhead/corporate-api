<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Vacation;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Vacation\Repository\VacationRepository;
use App\Domain\Vacation\Service\UpdateVacationService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Vacation\UpdateVacationDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/updateVacation', methods: [Request::METHOD_POST])]
readonly class UpdateVacation
{
    public function __construct(
        private Responder $responder,
        private UpdateVacationDenormalizer $updateVacationDenormalizer,
        private VacationRepository $vacationRepository,
        private UpdateVacationService $updateVacationService,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $dto = $this->updateVacationDenormalizer->denormalize($payload);
        $vacation = $this->vacationRepository->getByIdOwnedByEmployeeOrFail(id: $dto->vacationId, employee: $employee);

        $this->updateVacationService->update(vacation: $vacation, period: $dto->period);

        return $this->responder->success();
    }
}
