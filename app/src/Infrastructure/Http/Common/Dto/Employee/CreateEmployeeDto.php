<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Employee;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Employee\Enum\RolesEnum;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

readonly class CreateEmployeeDto
{
    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $password
     */
    public function __construct(
        public string $name,
        public Email $email,
        public string $password,
        public RolesEnum $role,
        public ?DateTimeImmutable $birthDate,
        public ?Uuid $departmentId,
        public ?Uuid $supervisingId,
    ) {
    }
}
