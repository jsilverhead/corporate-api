<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Auth;

use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class RefreshAccessTokenDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private StringDenormalizer $stringDenormalizer,
    ) {
    }

    /**
     * @psalm-return non-empty-string
     */
    public function denormalize(Payload $payload): string
    {
        /**
         * @psalm-var array{
         *     refreshToken: non-empty-string
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'refreshToken' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): string => $this->stringDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return $denormalizedData['refreshToken'];
    }
}
