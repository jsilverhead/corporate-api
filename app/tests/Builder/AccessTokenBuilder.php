<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\AccessToken\AccessToken;
use App\Domain\AccessToken\Service\CreateAccessTokenService;
use App\Domain\Employee\Employee;
use Doctrine\ORM\EntityManagerInterface;

readonly class AccessTokenBuilder
{
    public function __construct(
        private CreateAccessTokenService $createAccessTokenService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function build(Employee $employee): AccessToken
    {
        $accessToken = $this->createAccessTokenService->create(employee: $employee);

        $this->entityManager->flush();

        return $accessToken;
    }
}
