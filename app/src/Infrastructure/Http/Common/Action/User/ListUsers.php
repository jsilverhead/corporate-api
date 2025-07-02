<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\User;

use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\User\ListUsersDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\User\ListUsersNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/listUsers', methods: [Request::METHOD_GET])]
readonly class ListUsers
{
    public function __construct(
        private Responder $responder,
        private UserRepository $userRepository,
        private ListUsersDenormalizer $listUsersDenormalizer,
        private ListUsersNormalizer $listUsersNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] User $user, Payload $payload): Response
    {
        $dto = $this->listUsersDenormalizer->denormalize($payload);

        $users = $this->userRepository->listUsers(
            count: $dto->pagination->count,
            offset: $dto->pagination->offset,
            searchWords: $dto->searchWords,
        );

        $normalizedData = $this->listUsersNormalizer->normalize($users);

        return $this->responder->success($normalizedData);
    }
}
