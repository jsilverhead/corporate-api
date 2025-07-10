<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Department;

use App\Domain\Department\Repository\DepartmentRepository;
use App\Domain\Department\Service\AddEmployeeService;
use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Department\AddEmployeeDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/addEmployee', methods: [Request::METHOD_POST])]
readonly class AddEmployee
{
    public function __construct(
        private Responder $responder,
        private AddEmployeeDenormalizer $addEmployeeDenormalizer,
        private AddEmployeeService $addEmployeeService,
        private EntityManagerInterface $entityManager,
        private DepartmentRepository $departmentRepository,
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] User $user, Payload $payload): Response
    {
        $dto = $this->addEmployeeDenormalizer->denormalize($payload);
        $user = $this->userRepository->getByIdOrFail($dto->userId);
        $department = $this->departmentRepository->getByIdOrFail($dto->departmentId);

        $this->addEmployeeService->add(employee: $user, department: $department);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
