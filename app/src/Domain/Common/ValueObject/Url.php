<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use App\Domain\Common\Exception\UrlIsInvalidException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

readonly class Url
{
    #[Assert\NotBlank]
    #[Assert\Url(relativeProtocol: true)]
    public string $url;

    /**
     * @psalm-param non-empty-string $url
     */
    private function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @psalm-param non-empty-string $urlString
     *
     * @throws UrlIsInvalidException
     */
    public static function tryCreateFromString($urlString): self
    {
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();

        $url = new self($urlString);
        $constraintViolationList = $validator->validate($url);

        if (\count($constraintViolationList) > 0) {
            throw new UrlIsInvalidException();
        }

        return $url;
    }

    public function __toString(): string
    {
        return $this->url;
    }
}
