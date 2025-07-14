<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use App\Domain\AccessToken\Exception\ExpiredAccessTokenException;
use App\Domain\AccessToken\Exception\UnknownTokenException;
use App\Domain\AccessToken\JwtAuthSettings;
use App\Domain\AccessToken\Repository\AccessTokenRepository;
use App\Domain\Common\Exception\Jwt\JwtTokenIsInvalidException;
use App\Infrastructure\Auth\Exception\AuthorizationHeaderMissingException;
use App\Infrastructure\Payload\Exception\InvalidAuthorizationHeaderException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use UnexpectedValueException;

class BearerAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly AccessTokenRepository $accessTokenRepository,
        private readonly JwtAuthSettings $jwtAuthSettings,
    ) {
    }

    public function authenticate(Request $request): Passport
    {
        $authorization = $request->headers->get('Authorization');

        if (null === $authorization) {
            throw new AuthorizationHeaderMissingException();
        }

        $split = explode(' ', $authorization);

        if (2 !== \count($split) || 'Bearer' !== $split[0] || '' === $split[1]) {
            throw new InvalidAuthorizationHeaderException();
        }

        $accessToken = $split[1];

        try {
            JWT::decode(
                $accessToken,
                new Key(keyMaterial: $this->jwtAuthSettings->secret, algorithm: $this->jwtAuthSettings->algorithm),
            );
        } catch (ExpiredException) {
            throw new ExpiredAccessTokenException();
        } catch (SignatureInvalidException | UnexpectedValueException) {
            throw new JwtTokenIsInvalidException();
        }

        $token = $this->accessTokenRepository->getWithUserByAccessToken($accessToken);

        if (null === $token) {
            throw new UnknownTokenException();
        }

        try {
            JWT::decode(
                $token->accessToken,
                new Key(keyMaterial: $this->jwtAuthSettings->secret, algorithm: $this->jwtAuthSettings->algorithm),
            );
        } catch (ExpiredException) {
            throw new ExpiredAccessTokenException();
        } catch (SignatureInvalidException | UnexpectedValueException) {
            throw new JwtTokenIsInvalidException();
        }

        return new SelfValidatingPassport(
            new UserBadge(
                userIdentifier: $token->employee->id->toRfc4122(),
                userLoader: static fn(): UserInterface => $token->employee,
            ),
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return null;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }
}
