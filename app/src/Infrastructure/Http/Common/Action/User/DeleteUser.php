<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\User;

use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Service\DeleteUserService;
use App\Domain\User\User;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\User\DeleteUserDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/deleteUser', methods: [Request::METHOD_POST])]
readonly class DeleteUser
{
    public function __construct(
        private Responder $responder,
        private DeleteUserService $deleteUserService,
        private DeleteUserDenormalizer $deleteUserDenormalizer,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] User $user, Payload $payload): Response
    {
        $id = $this->deleteUserDenormalizer->denormalize($payload);

        $user = $this->userRepository->getByIdOrFail($id);

        $this->deleteUserService->delete($user);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
