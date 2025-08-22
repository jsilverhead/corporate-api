<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Vacation;

use App\Domain\Department\Repository\DepartmentRepository;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Repository\EmployeeRepository;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Vacation\ListVacationsDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Vacation\ListVacationsNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/listVacations', methods: [Request::METHOD_GET])]
readonly class ListVacations
{
    public function __construct(
        private Responder $responder,
        private ListVacationsDenormalizer $listVacationsDenormalizer,
        private DepartmentRepository $departmentRepository,
        private EmployeeRepository $employeeRepository,
        private ListVacationsNormalizer $listVacationsNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(
        #[AllowedUserRole([RolesEnum::SUPERUSER, RolesEnum::USER])] Employee $employee,
        Payload $payload,
    ): Response {
        $dto = $this->listVacationsDenormalizer->denormalize($payload);
        $department = $employee = null;

        if (null !== $dto->employeeId) {
            $employee = $this->employeeRepository->getByIdOrFail($dto->employeeId);
        }

        if (null !== $dto->departmentId) {
            $department = $this->departmentRepository->getByIdOrFail($dto->departmentId);
        }

        $vacations = $this->departmentRepository->listVacations(
            period: $dto->period,
            status: $dto->status,
            employee: $employee,
            department: $department,
            count: $dto->pagination->count,
            offset: $dto->pagination->offset,
        );

        $normalizedData = $this->listVacationsNormalizer->normalize($vacations);

        return $this->responder->success($normalizedData);
    }
}
