<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Vacation;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Vacation\Repository\VacationRepository;
use App\Domain\Vacation\Service\DeleteVacationService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Vacation\DeleteVacationDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/deleteVacation', methods: [Request::METHOD_POST])]
readonly class DeleteVacation
{
    public function __construct(
        private Responder $responder,
        private DeleteVacationDenormalizer $deleteVacationDenormalizer,
        private VacationRepository $vacationRepository,
        private DeleteVacationService $deleteVacationService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $id = $this->deleteVacationDenormalizer->denormalize($payload);
        $vacation = $this->vacationRepository->getByIdOwnedByEmployeeOrFail(id: $id, employee: $employee);

        $this->deleteVacationService->delete($vacation);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
