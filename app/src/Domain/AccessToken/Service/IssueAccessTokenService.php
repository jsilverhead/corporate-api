<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Service;

use App\Domain\AccessToken\AccessToken;
use App\Domain\AccessToken\Exception\PasswordIsInvalidException;
use App\Domain\User\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class IssueAccessTokenService
{
    public function __construct(
        private CreateAccessTokenService $createAccessTokenService,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @psalm-param non-empty-string $password
     */
    public function issue(User $user, string $password): AccessToken
    {
        $isPasswordValid = $this->passwordHasher->isPasswordValid($user, $password);

        if (!$isPasswordValid) {
            throw new PasswordIsInvalidException();
        }

        return $this->createAccessTokenService->create($user);
    }
}
