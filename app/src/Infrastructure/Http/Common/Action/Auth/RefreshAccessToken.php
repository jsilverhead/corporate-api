<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Auth;

use App\Domain\AccessToken\Service\RefreshAccessTokenService;
use App\Infrastructure\Http\Common\Denormalizer\Auth\RefreshAccessTokenDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Auth\RefreshTokenNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/refreshToken', methods: [Request::METHOD_POST])]
readonly class RefreshAccessToken
{
    public function __construct(
        private Responder $responder,
        private RefreshAccessTokenDenormalizer $refreshAccessTokenDenormalizer,
        private RefreshAccessTokenService $refreshAccessTokenService,
        private RefreshTokenNormalizer $refreshTokenNormalizer,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Payload $payload): Response
    {
        $refreshToken = $this->refreshAccessTokenDenormalizer->denormalize($payload);

        $accessToken = $this->refreshAccessTokenService->refresh($refreshToken);

        $this->entityManager->flush();

        $normalizedData = $this->refreshTokenNormalizer->normalize($accessToken);

        return $this->responder->success($normalizedData);
    }
}
