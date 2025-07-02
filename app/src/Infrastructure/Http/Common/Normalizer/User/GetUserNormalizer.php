<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\User;

use App\Domain\User\User;

class GetUserNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(User $user): array
    {
        return [
            'id' => $user->id->toRfc4122(),
            'name' => $user->name,
            'email' => $user->email->email,
            'role' => $user->role->value,
            'birthDate' => $user->birthDate?->format('Y-m-d'),
        ];
    }
}
