<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Department;

use Symfony\Component\Uid\Uuid;

class RemoveSupervisorDto
{
    public function __construct(public Uuid $userId, public Uuid $departmentId)
    {
    }
}
