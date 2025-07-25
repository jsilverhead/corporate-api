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
use Doctrine\ORM\EntityManagerInterface;
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
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::SUPERUSER, RolesEnum::USER])] Employee $employee,
        Payload $payload,
    ): Response {
        $dto = $this->updateVacationDenormalizer->denormalize($payload);

        $vacation =
            RolesEnum::SUPERUSER === $employee->role
                ? $this->vacationRepository->getByIdOrFail($dto->vacationId)
                : $this->vacationRepository->getByIdAndEmployeeOrFail(employee: $employee, id: $dto->vacationId);

        $this->updateVacationService->update(vacation: $vacation, period: $dto->period);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
