<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Department;

use App\Domain\Department\Repository\DepartmentRepository;
use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\User;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Department\ListDepartmentsDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Department\ListDepartmentsNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/listDepartments', methods: [Request::METHOD_GET])]
readonly class ListDepartments
{
    public function __construct(
        private Responder $responder,
        private DepartmentRepository $departmentRepository,
        private ListDepartmentsDenormalizer $listDepartmentsDenormalizer,
        private ListDepartmentsNormalizer $listDepartmentsNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(
        #[AllowedUserRole([RolesEnum::SUPERUSER, RolesEnum::USER])] User $user,
        Payload $payload,
    ): Response {
        $dto = $this->listDepartmentsDenormalizer->denormalize($payload);

        $departments = $this->departmentRepository->listDepartments(
            searchWords: $dto->searchWords,
            count: $dto->pagination->count,
            offset: $dto->pagination->offset,
        );

        $normalizedData = $this->listDepartmentsNormalizer->normalize($departments);

        return $this->responder->success($normalizedData);
    }
}
