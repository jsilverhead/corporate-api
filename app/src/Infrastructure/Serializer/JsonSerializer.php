<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use JsonException;
use RuntimeException;

readonly class JsonSerializer
{
    public function __construct(private bool $ensureNoObjects, private bool $sortKeys)
    {
    }

    /**
     * @psalm-param array<array-key, mixed> $data
     *
     * @throws JsonException
     */
    public function serialize(array $data): string
    {
        if ($this->ensureNoObjects) {
            $this->ensureNoObjects($data);
        }

        if ($this->sortKeys) {
            $this->sortKeys($data);
        }

        return $this->encodeJson($data);
    }

    /**
     * @psalm-param array<array-key, mixed> $data
     *
     * @throws JsonException
     */
    private function encodeJson(array $data): string
    {
        /** @psalm-var string|false $json */
        $json = json_encode($data, \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES | \JSON_THROW_ON_ERROR);

        if (false === $json) {
            throw new JsonException();
        }

        return $json;
    }

    /**
     * @psalm-param array<array-key, mixed> $values
     */
    private function ensureNoObjects(array $values): void
    {
        /** @psalm-var mixed $value */
        foreach ($values as $value) {
            if (\is_object($value)) {
                throw new RuntimeException('You are trying to call json_encode with value containing an object');
            }

            if (\is_array($value)) {
                $this->ensureNoObjects($value);
            }
        }
    }

    /**
     * @psalm-param array<array-key, mixed> $array
     */
    private function sortKeys(array &$array): void
    {
        /** @psalm-var mixed $value */
        foreach ($array as &$value) {
            if (\is_array($value)) {
                $this->sortKeys($value);
            }
        }

        unset($value);
        ksort($array);
    }
}
