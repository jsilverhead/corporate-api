<?php

declare(strict_types=1);

namespace App\Infrastructure\Responder\ApiProblem;

class ConcurrentAccessToEntity implements ApiProblemInterface
{
    public function getAdditionalFields(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return 'Something went wrong during concurrent access to entity by multiple requests. Please retry your request.';
    }

    public function getType(): string
    {
        return 'concurrent_access_to_entity';
    }
}
