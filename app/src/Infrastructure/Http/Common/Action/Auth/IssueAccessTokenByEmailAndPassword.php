<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Auth;

use App\Domain\AccessToken\Service\IssueAccessTokenService;
use App\Domain\Employee\Repository\EmployeeRepository;
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
        private EmployeeRepository $employeeRepository,
        private IssueAccessTokenService $issueAccessTokenService,
        private IssueAccessTokenByEmailAndPasswordDenormalizer $issueAccessTokenByEmailAndPasswordDenormalizer,
        private IssueAccessTokenByEmailAndPasswordNormalizer $issueAccessTokenByEmailAndPasswordNormalizer,
    ) {
    }

    public function __invoke(Payload $payload): Response
    {
        $dto = $this->issueAccessTokenByEmailAndPasswordDenormalizer->denormalize($payload);

        $employee = $this->employeeRepository->getByEmailOrFail($dto->email);
        $accessToken = $this->issueAccessTokenService->issue(employee: $employee, password: $dto->password);

        $normalizedData = $this->issueAccessTokenByEmailAndPasswordNormalizer->normalize($accessToken);

        return $this->responder->success($normalizedData);
    }
}
