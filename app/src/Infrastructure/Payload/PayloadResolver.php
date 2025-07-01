<?php

declare(strict_types=1);

namespace App\Infrastructure\Payload;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class PayloadResolver implements ValueResolverInterface
{
    public function __construct(private PayloadDeserializer $payloadDeserializer)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (Payload::class !== $argument->getType()) {
            return [];
        }

        yield $this->payloadDeserializer->deserialize($request);
    }
}
