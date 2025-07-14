<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Employee;

use App\Domain\Employee\Enum\RolesEnum;
use Symfony\Component\Uid\Uuid;

readonly class UpdateEmployeeDto
{
    /**
     * @psalm-param non-empty-string $name
     */
    public function __construct(public Uuid $employeeId, public string $name, public RolesEnum $role)
    {
    }
}
