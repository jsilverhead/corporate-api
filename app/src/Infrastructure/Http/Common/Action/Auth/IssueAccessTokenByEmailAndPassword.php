<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Auth;

use App\Domain\AccessToken\Service\IssueAccessTokenService;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Http\Common\Denormalizer\Auth\IssueAccessTokenByEmailAndPasswordDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Auth\IssueAccessTokenByEmailAndPasswordNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/issueTokenByEmailAndPassword', methods: [Request::METHOD_POST])]
readonly class IssueAccessTokenByEmailAndPassword
{
    public function __construct(
        private Responder $responder,
        private UserRepository $userRepository,
        private IssueAccessTokenService $issueAccessTokenService,
        private IssueAccessTokenByEmailAndPasswordDenormalizer $issueAccessTokenByEmailAndPasswordDenormalizer,
        private IssueAccessTokenByEmailAndPasswordNormalizer $issueAccessTokenByEmailAndPasswordNormalizer,
    ) {
    }

    public function __invoke(Payload $payload): Response
    {
        $dto = $this->issueAccessTokenByEmailAndPasswordDenormalizer->denormalize($payload);

        $user = $this->userRepository->getByEmailOrFail($dto->email);

        $accessToken = $this->issueAccessTokenService->issue(user: $user, password: $dto->password);

        $normalizedData = $this->issueAccessTokenByEmailAndPasswordNormalizer->normalize($accessToken);

        return $this->responder->success($normalizedData);
    }
}
