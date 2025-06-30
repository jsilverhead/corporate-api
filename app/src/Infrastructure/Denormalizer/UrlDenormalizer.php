<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use App\Domain\Common\Exception\UrlIsInvalidException;
use App\Domain\Common\ValueObject\Url;
use App\Infrastructure\Denormalizer\ConstraintViolation\UrlIsNotValid;
use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\Exception\ValidationError;
use Spiks\UserInputProcessor\Pointer;

readonly class UrlDenormalizer
{
    public function __construct(private StringDenormalizer $stringDenormalizer)
    {
    }

    /**
     * It expects `$data` to be string of url. see: https://datatracker.ietf.org/doc/html/rfc3986.
     */
    public function denormalize(mixed $data, Pointer $pointer): Url
    {
        try {
            /** @psalm-var non-empty-string $urlString */
            $urlString = $this->stringDenormalizer->denormalize($data, $pointer, minLength: 1);
            $url = Url::tryCreateFromString($urlString);
        } catch (ValidationError | UrlIsInvalidException) {
            throw new ValidationError([new UrlIsNotValid($pointer)]);
        }

        return $url;
    }
}
