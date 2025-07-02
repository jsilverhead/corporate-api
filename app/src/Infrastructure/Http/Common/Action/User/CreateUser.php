<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\User;

use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Service\CreateUserService;
use App\Domain\User\User;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\User\CreateUserDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\User\CreateUserNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/createUser', methods: [Request::METHOD_POST])]
readonly class CreateUser
{
    public function __construct(
        private Responder $responder,
        private CreateUserService $createUserService,
        private CreateUserDenormalizer $createUserDenormalizer,
        private EntityManagerInterface $entityManager,
        private CreateUserNormalizer $createUserNormalizer,
    ) {
    }

    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] User $user, Payload $payload): Response
    {
        // TODO: Добавить MessageBusInterface + генерацию письма
        $dto = $this->createUserDenormalizer->denormalize($payload);

        $this->createUserService->create(
            name: $dto->name,
            email: $dto->email,
            password: $dto->password,
            role: $dto->role,
            birthDate: $dto->birthDate,
        );

        $this->entityManager->flush();

        $normalizedData = $this->createUserNormalizer->normalize($user);

        return $this->responder->success($normalizedData);
    }
}
