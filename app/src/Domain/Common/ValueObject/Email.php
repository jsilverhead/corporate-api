<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use App\Domain\Common\Exception\EmailIsInvalidException;
use App\Infrastructure\Validator\ValidatorFactory;
use Symfony\Component\Validator\Constraints as Assert;

readonly class Email
{
    #[Assert\NotBlank]
    #[Assert\Email(mode: 'strict')]
    public string $email;

    /**
     * @psalm-param non-empty-string $emailString
     */
    private function __construct(string $emailString)
    {
        $this->email = $emailString;
    }

    /**
     * @psalm-param non-empty-string $emailString
     *
     * @throws EmailIsInvalidException
     */
    public static function tryCreateFromString($emailString): self
    {
        $email = new self($emailString);
        $constraintViolationList = ValidatorFactory::getValidator()->validate($email);

        if (\count($constraintViolationList) > 0) {
            throw new EmailIsInvalidException();
        }

        return $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
