<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\User;

use App\Domain\User\User;

class CreateUserNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(User $user): array
    {
        return [
            'id' => $user->id->toRfc4122(),
        ];
    }
}
