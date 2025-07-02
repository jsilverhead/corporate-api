<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\User;

use App\Domain\User\User;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ListUsersNormalizer
{
    public function __construct()
    {
    }

    /**
     * @psalm-param Paginator<User> $paginator
     *
     * @psalm-return non-empty-array
     */
    public function normalize(Paginator $paginator): array
    {
        return [
            'items' => array_map(
                static fn(User $user): array => [
                    'id' => $user->id->toRfc4122(),
                    'name' => $user->name,
                    'email' => $user->email->email,
                    'role' => $user->role->value,
                ],
                (array) $paginator->getIterator(),
            ),
            'itemsAmount' => $paginator->count(),
        ];
    }
}
