<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Department\Department;
use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Exception\UserWithThisEmailAlreadyExistsException;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class CreateUserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $password
     */
    public function create(
        string $name,
        Email $email,
        string $password,
        RolesEnum $role,
        ?DateTimeImmutable $birthDate,
        ?Department $department = null,
        ?Department $supervising = null,
    ): User {
        $isUserWithEmailExists = $this->userRepository->isUserWithEmailExists($email);

        if ($isUserWithEmailExists) {
            throw new UserWithThisEmailAlreadyExistsException();
        }

        $user = new User(
            name: $name,
            email: $email,
            role: $role,
            birthDate: $birthDate,
            department: $department,
            supervising: $supervising,
        );

        /**
         * @psalm-var non-empty-string $hashedPassword
         */
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

        $user->password = $hashedPassword;

        $this->userRepository->add($user);

        return $user;
    }
}
