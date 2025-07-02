<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Auth;

use App\Domain\Common\ValueObject\Email;

class IssueAccessTokenByEmailAndPasswordDto
{
    /**
     * @psalm-param non-empty-string $password
     */
    public function __construct(public Email $email, public string $password)
    {
    }
}
