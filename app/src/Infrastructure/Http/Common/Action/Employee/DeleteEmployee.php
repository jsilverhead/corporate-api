<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Employee;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Repository\EmployeeRepository;
use App\Domain\Employee\Service\DeleteEmployeeService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Employee\DeleteEmployeeDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/deleteEmployee', methods: [Request::METHOD_POST])]
readonly class DeleteEmployee
{
    public function __construct(
        private Responder $responder,
        private DeleteEmployeeService $deleteUserService,
        private DeleteEmployeeDenormalizer $deleteEmployeeDenormalizer,
        private EmployeeRepository $employeeRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $id = $this->deleteEmployeeDenormalizer->denormalize($payload);
        $employee = $this->employeeRepository->getByIdOrFail($id);

        $this->deleteUserService->delete($employee);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
