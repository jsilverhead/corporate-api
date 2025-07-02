<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\User;

use App\Domain\Common\ValueObject\Email;
use App\Domain\User\Enum\RolesEnum;
use DateTimeImmutable;

readonly class CreateUserDto
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
    ) {
    }
}
