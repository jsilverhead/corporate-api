<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Department;

use App\Domain\Department\Service\CreateDepartmentService;
use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\User;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\CreateDepartmentDenormalizer\CreateDepartmentDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Department\CreateDepartmentNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/createDepartment', methods: [Request::METHOD_POST])]
readonly class CreateDepartment
{
    public function __construct(
        private Responder $responder,
        private CreateDepartmentService $createDepartmentService,
        private EntityManagerInterface $entityManager,
        private CreateDepartmentDenormalizer $createDepartmentDenormalizer,
        private CreateDepartmentNormalizer $createDepartmentNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] User $user, Payload $payload): Response
    {
        $name = $this->createDepartmentDenormalizer->denormalize($payload);

        $department = $this->createDepartmentService->create($name);

        $this->entityManager->flush();

        $normalizedData = $this->createDepartmentNormalizer->normalize($department);

        return $this->responder->success($normalizedData);
    }
}
