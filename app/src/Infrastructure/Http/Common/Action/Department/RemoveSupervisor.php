<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Department;

use App\Domain\Department\Repository\DepartmentRepository;
use App\Domain\Department\Service\RemoveSupervisorService;
use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Department\RemoveSupervisorDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/removeSupervisor', methods: [Request::METHOD_POST])]
readonly class RemoveSupervisor
{
    public function __construct(
        private Responder $responder,
        private RemoveSupervisorService $removeSupervisorService,
        private RemoveSupervisorDenormalizer $removeSupervisorDenormalizer,
        private UserRepository $userRepository,
        private DepartmentRepository $departmentRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] User $user, Payload $payload): Response
    {
        $dto = $this->removeSupervisorDenormalizer->denormalize($payload);
        $user = $this->userRepository->getByIdOrFail($dto->userId);
        $department = $this->departmentRepository->getByIdOrFail($dto->departmentId);

        $this->removeSupervisorService->remove(department: $department, supervisor: $user);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
