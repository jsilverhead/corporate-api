<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Employee;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Repository\EmployeeRepository;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Employee\GetEmployeeDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Employee\GetEmployeeNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/getEmployee', methods: [Request::METHOD_GET])]
readonly class GetEmployee
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private Responder $responder,
        private GetEmployeeDenormalizer $getEmployeeDenormalizer,
        private GetEmployeeNormalizer $getEmployeeNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $id = $this->getEmployeeDenormalizer->denormalize($payload);
        $employee = $this->employeeRepository->getByIdWithDepartmentOrFail($id);

        $normalizedData = $this->getEmployeeNormalizer->normalize($employee);

        return $this->responder->success($normalizedData);
    }
}
