<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Employee;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Repository\EmployeeRepository;
use App\Domain\Employee\Service\UpdateEmployeeService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Employee\UpdateEmployeeDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/updateEmployee', methods: [Request::METHOD_POST])]
readonly class UpdateEmployee
{
    public function __construct(
        private Responder $responder,
        private UpdateEmployeeService $updateUserService,
        private EntityManagerInterface $entityManager,
        private UpdateEmployeeDenormalizer $updateUserDenormalizer,
        private EmployeeRepository $employeeRepository,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $dto = $this->updateUserDenormalizer->denormalize($payload);
        $employee = $this->employeeRepository->getByIdOrFail($dto->employeeId);

        $this->updateUserService->update(employee: $employee, name: $dto->name, role: $dto->role);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
