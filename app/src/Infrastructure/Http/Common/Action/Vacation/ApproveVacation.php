<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Vacation;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Vacation\Repository\VacationRepository;
use App\Domain\Vacation\Service\ApproveVacationService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Vacation\ApproveVacationDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/approveVacation', methods: [Request::METHOD_POST])]
readonly class ApproveVacation
{
    public function __construct(
        private Responder $responder,
        private ApproveVacationDenormalizer $approveVacationDenormalizer,
        private VacationRepository $vacationRepository,
        private ApproveVacationService $approveVacationService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $id = $this->approveVacationDenormalizer->denormalize($payload);
        $vacation = $this->vacationRepository->getVacationByIdSupervisedByEmployeeOrFail(id: $id, employee: $employee);

        $this->approveVacationService->approve($vacation);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
