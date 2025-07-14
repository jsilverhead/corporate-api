<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Department;

use Symfony\Component\Uid\Uuid;

class AddSupervisorDto
{
    public function __construct(public Uuid $departmentId, public Uuid $employeeId)
    {
    }
}
