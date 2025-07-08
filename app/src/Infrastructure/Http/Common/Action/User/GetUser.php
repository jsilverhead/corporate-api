<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\User;

use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\User\GetUserDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\User\GetUserNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/getUser', methods: [Request::METHOD_GET])]
class GetUser
{
    public function __construct(
        private UserRepository $userRepository,
        private Responder $responder,
        private GetUserDenormalizer $getUserDenormalizer,
        private GetUserNormalizer $getUserNormalizer,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] User $user, Payload $payload): Response
    {
        $id = $this->getUserDenormalizer->denormalize($payload);

        $receivedUser = $this->userRepository->getByIdWithDepartmentOrFail($id);

        $normalizedData = $this->getUserNormalizer->normalize($receivedUser);

        return $this->responder->success($normalizedData);
    }
}
