<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\News;

use App\Infrastructure\Denormalizer\ContentDenormalizer;
use App\Infrastructure\Denormalizer\TitleDenormalizer;
use App\Infrastructure\Denormalizer\UuidDenormalizer;
use App\Infrastructure\Http\Common\Dto\News\UpdateNewsDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;
use Symfony\Component\Uid\Uuid;

readonly class UpdateNewsDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private UuidDenormalizer $uuidDenormalizer,
        private TitleDenormalizer $titleDenormalizer,
        private ContentDenormalizer $contentDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): UpdateNewsDto
    {
        /**
         * @psalm-var array{
         *     id: Uuid,
         *     title: non-empty-string,
         *     content: non-empty-string
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'id' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Uuid => $this->uuidDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'title' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): string => $this->titleDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'content' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): string => $this->contentDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return new UpdateNewsDto(
            id: $denormalizedData['id'],
            title: $denormalizedData['title'],
            content: $denormalizedData['content'],
        );
    }
}
