<?php

declare(strict_types=1);

namespace App\Infrastructure\Responder;

use App\Domain\Common\Exception\ServiceException;
use App\Infrastructure\Normalizer\ConstraintViolationNormalizer;
use App\Infrastructure\Responder\ApiProblem\ApiProblemInterface;
use App\Infrastructure\Serializer\JsonSerializer;
use JsonException;
use Spiks\UserInputProcessor\ConstraintViolation\ConstraintViolationInterface;
use Symfony\Component\HttpFoundation\Response;

readonly class Responder
{
    public function __construct(
        private JsonSerializer $serializer,
        private ConstraintViolationNormalizer $constraintViolationNormalizer,
    ) {
    }

    //    public function accessTokenExpired(): Response
    //    {
    //        return $this->error('access_token_expired', 'Access token is expired.');
    //    }
    //

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @throws JsonException
     */
    public function apiProblem(ApiProblemInterface $problem): Response
    {
        return $this->error(
            type: $problem->getType(),
            detail: $problem->getDescription(),
            additionalFields: $problem->getAdditionalFields(),
        );
    }
    //
    //    public function authorizationError(AuthorizationError $error): Response
    //    {
    //        return $this->error(type: $error->getType(), detail: $error->getDetail());
    //    }

    /**
     * @see https://datatracker.ietf.org/doc/html/draft-inadarei-api-health-check-05
     *
     * @throws JsonException
     */
    public function health(string $appVersion, string $apiVersion): Response
    {
        return new Response(
            content: $this->serializer->serialize([
                'status' => 'pass',
                'version' => $appVersion,
                'releaseId' => $apiVersion,
            ]),
            status: Response::HTTP_OK,
            headers: [
                'Content-Type' => 'application/health+json; charset=UTF-8',
            ],
        );
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @throws JsonException
     */
    public function incorrectApiMethodCall(string $message): Response
    {
        return $this->error(type: 'incorrect_api_method_call', detail: $message);
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @throws JsonException
     */
    public function internalServerError(): Response
    {
        // prettier-ignore
        return new Response(
            content: $this->serializer->serialize([
                'type' => 'internal_server_error',
                'detail' => 'Internal error occurred. Developers are noticed about that, however, if you are client ' .
                    'developer, you may text an issue about faced problem: how to reproduce it, what data did you ' .
                    'send and other useful information.',
            ]),
            status: Response::HTTP_INTERNAL_SERVER_ERROR,
            headers: [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
        );
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @throws JsonException
     */
    public function methodNotAllowed(): Response
    {
        return $this->error(
            type: 'method_not_allowed',
            detail: 'Requested resource does not support given HTTP method.',
        );
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @throws JsonException
     */
    public function notFound(): Response
    {
        return $this->error(type: 'not_found', detail: 'There are no endpoints at requested URL.');
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @throws JsonException
     */
    public function serviceError(ServiceException $error): Response
    {
        return $this->error(
            type: $error->getType(),
            detail: $error->getDescription(),
            additionalFields: $error->getAdditionalFields(),
        );
    }

    /**
     * @psalm-param non-empty-array $data
     *
     * @throws JsonException
     */
    public function success(?array $data = null): Response
    {
        if (null !== $data) {
            $data = [...$data, 'serverTime' => time()];
        }

        return new Response(
            content: null !== $data ? $this->serializer->serialize($data) : '',
            status: Response::HTTP_OK,
            headers: [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
        );
    }

    //    /**
    //     * @throws \JsonException
    //     */
    //    public function unauthorized(string $message = 'Authentication is required to access the route.'): Response
    //    {
    //        return $this->error('unauthorized', $message);
    //    }

    /**
     * @psalm-param list<ConstraintViolationInterface> $constraintViolations
     *
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @throws JsonException
     */
    public function validationError(array $constraintViolations): Response
    {
        return $this->error(
            type: 'validation_error',
            detail: 'Data passed via JSON body or query string contains invalid values.',
            additionalFields: [
                'violations' => $this->constraintViolationNormalizer->normalizeCollection($constraintViolations),
            ],
        );
    }

    /**
     * @psalm-param array<string, mixed> $additionalFields
     *
     * @throws JsonException
     */
    private function error(string $type, string $detail, array $additionalFields = []): Response
    {
        return new Response(
            content: $this->serializer->serialize([
                'type' => $type,
                'detail' => $detail,
                ...$additionalFields,
            ]),
            status: Response::HTTP_BAD_REQUEST,
            headers: [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
        );
    }
}
