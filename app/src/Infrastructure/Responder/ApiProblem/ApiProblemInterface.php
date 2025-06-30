<?php

declare(strict_types=1);

namespace App\Infrastructure\Responder\ApiProblem;

interface ApiProblemInterface
{
    /**
     * @psalm-return array<string, mixed>
     */
    public function getAdditionalFields(): array;

    public function getDescription(): string;

    public function getType(): string;
}
