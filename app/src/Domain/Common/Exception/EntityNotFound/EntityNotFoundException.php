<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception\EntityNotFound;

use App\Domain\Common\Exception\ServiceException;

class EntityNotFoundException extends ServiceException
{
    public function __construct(public readonly EntityNotFoundEnum $entity)
    {
        parent::__construct();
    }

    public function getAdditionalFields(): array
    {
        return [
            'entity' => $this->entity,
        ];
    }

    public function getDescription(): string
    {
        return 'Entity not found.';
    }

    public function getType(): string
    {
        return 'entity_not_found';
    }
}
