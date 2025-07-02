<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\User;

use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Service\UpdateUserService;
use App\Domain\User\User;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\User\UpdateUserDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/updateUser', methods: [Request::METHOD_POST])]
readonly class UpdateUser
{
    public function __construct(
        private Responder $responder,
        private UpdateUserService $updateUserService,
        private EntityManagerInterface $entityManager,
        private UpdateUserDenormalizer $updateUserDenormalizer,
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] User $user, Payload $payload): Response
    {
        $dto = $this->updateUserDenormalizer->denormalize($payload);

        $userForUpdate = $this->userRepository->getByIdOrFail($dto->userId);

        $this->updateUserService->update(user: $userForUpdate, name: $dto->name, role: $dto->role);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
