<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception\EntityNotFound;

use App\Domain\Common\Exception\ServiceException;
use Symfony\Component\Uid\Uuid;

class EntitiesNotFoundByIdsException extends ServiceException
{
    public function __construct(private EntityNotFoundEnum $entity, private array $ids)
    {
        parent::__construct();
    }

    public function getAdditionalFields(): array
    {
        return [
            'entity' => $this->entity,
            'ids' => array_map(static fn(Uuid $id) => $id->toRfc4122(), $this->ids),
        ];
    }

    public function getDescription(): string
    {
        return 'Entity not found by ids.';
    }

    public function getType(): string
    {
        return 'entities_not_found_by_ids';
    }
}
