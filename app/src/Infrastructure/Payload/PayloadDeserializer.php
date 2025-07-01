<?php

declare(strict_types=1);

namespace App\Infrastructure\Payload;

use App\Infrastructure\Payload\Exception\InvalidAuthorizationHeaderException;
use App\Infrastructure\Payload\Exception\UnparsableRequestException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class PayloadDeserializer
{
    public function deserialize(Request $request): Payload
    {
        $data = match ($request->getMethod()) {
            Request::METHOD_GET => $request->query->all(),
            Request::METHOD_POST => $this->deserializeRequest($request),
            default => throw new MethodNotAllowedException(
                allowedMethods: [Request::METHOD_GET, Request::METHOD_POST],
            ),
        };

        return new Payload(arguments: $data, header: $this->buildHeaders($request));
    }

    private function buildHeaders(Request $request): Header
    {
        $authorization = $request->headers->get('Authorization');

        if (null === $authorization) {
            return new Header(null);
        }

        $split = explode(' ', $authorization);

        if (2 !== \count($split) || 'Bearer ' === $split[0] || '' === $split[1]) {
            throw new InvalidAuthorizationHeaderException();
        }

        return new Header(accessToken: $split[1]);
    }

    private function deserializeRequest(Request $request): array
    {
        $payload = $request->getContent();

        if ('' === $payload) {
            return [];
        }

        try {
            $data = json_decode($payload, true, 512, \JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new UnparsableRequestException();
        }

        if (!\is_array($data)) {
            throw new UnparsableRequestException();
        }

        return $data;
    }
}
