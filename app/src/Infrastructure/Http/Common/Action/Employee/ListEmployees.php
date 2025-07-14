<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Employee;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Repository\EmployeeRepository;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Employee\ListEmployeesDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Employee\ListEmployeesNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/listEmployees', methods: [Request::METHOD_GET])]
readonly class ListEmployees
{
    public function __construct(
        private Responder $responder,
        private EmployeeRepository $employeeRepository,
        private ListEmployeesDenormalizer $employeesDenormalizer,
        private ListEmployeesNormalizer $listEmployeesNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $dto = $this->employeesDenormalizer->denormalize($payload);
        $employees = $this->employeeRepository->listEmployees(
            count: $dto->pagination->count,
            offset: $dto->pagination->offset,
            searchWords: $dto->searchWords,
        );

        $normalizedData = $this->listEmployeesNormalizer->normalize($employees);

        return $this->responder->success($normalizedData);
    }
}
