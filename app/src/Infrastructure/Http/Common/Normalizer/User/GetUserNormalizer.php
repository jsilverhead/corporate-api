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
        $department = null;

        if (null !== $user->department) {
            $department = ['id' => $user->department->id->toRfc4122(), 'name' => $user->department->name];
        }

        $supervising = null;

        if (null !== $user->supervising) {
            $supervising = ['id' => $user->supervising->id->toRfc4122(), 'name' => $user->supervising->name];
        }

        return [
            'id' => $user->id->toRfc4122(),
            'name' => $user->name,
            'email' => $user->email->email,
            'role' => $user->role->value,
            'birthDate' => $user->birthDate?->format('Y-m-d'),
            'department' => $department,
            'supervising' => $supervising,
        ];
    }
}
