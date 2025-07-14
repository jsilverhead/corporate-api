<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Department;

use App\Domain\Department\Repository\DepartmentRepository;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Department\GetDepartmentDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Department\GetDepartmentNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/getDepartment', methods: [Request::METHOD_GET])]
readonly class GetDepartment
{
    public function __construct(
        private Responder $responder,
        private GetDepartmentDenormalizer $getDepartmentDenormalizer,
        private DepartmentRepository $departmentRepository,
        private GetDepartmentNormalizer $getDepartmentNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(
        #[AllowedUserRole([RolesEnum::SUPERUSER, RolesEnum::USER])] Employee $user,
        Payload $payload,
    ): Response {
        $id = $this->getDepartmentDenormalizer->denormalize($payload);

        $department = $this->departmentRepository->getByIdOrFail($id);

        $normalizedData = $this->getDepartmentNormalizer->normalize($department);

        return $this->responder->success($normalizedData);
    }
}
