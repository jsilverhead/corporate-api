<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Auth;

use App\Domain\AccessToken\AccessToken;
use App\Infrastructure\Normalizer\DateTimeNormalizer;

class IssueAccessTokenByEmailAndPasswordNormalizer
{
    public function __construct(private DateTimeNormalizer $dateTimeNormalizer)
    {
    }

    /**
     * @psalm-return non-empty-array
     */
    public function normalize(AccessToken $accessToken): array
    {
        return [
            'userId' => $accessToken->user->id->toRfc4122(),
            'accessToken' => $accessToken->accessToken,
            'refreshToken' => $accessToken->refreshToken,
            'accessTokenExpiresAt' => $this->dateTimeNormalizer->normalize($accessToken->accessTokenExpiresAt),
            'refreshTokenExpiresAt' => $this->dateTimeNormalizer->normalize($accessToken->accessTokenExpiresAt),
            'role' => $accessToken->user->role->value,
        ];
    }
}
