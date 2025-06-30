<?php

declare(strict_types=1);

namespace App\Infrastructure\Responder;

use RuntimeException;

class IncorrectApiMethodCallException extends RuntimeException
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function accessTokenFieldShouldBeString(): self
    {
        return new self('"accessToken" field in JSON should be a string.');
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function argumentsFieldShouldBeObject(): self
    {
        return new self('"arguments" field in JSON should be an object.');
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function argumentsFieldShouldExist(): self
    {
        return new self('"arguments" field should be presented in this request.');
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function jsonShouldBeAnObject(): self
    {
        return new self('Passed JSON should be an object.');
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function requestBodyMissing(): self
    {
        return new self('HTTP request body should contain JSON.');
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function wrongJsonSyntax(): self
    {
        return new self('Passed JSON is not parseable, it has wrong syntax.');
    }
}
