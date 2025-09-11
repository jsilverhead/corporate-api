<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\News;

use App\Infrastructure\Denormalizer\ContentDenormalizer;
use App\Infrastructure\Denormalizer\TitleDenormalizer;
use App\Infrastructure\Http\Common\Dto\News\AddNewsDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

readonly class AddNewsDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private TitleDenormalizer $titleDenormalizer,
        private ContentDenormalizer $contentDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): AddNewsDto
    {
        /**
         * @psalm-var array{
         *     title: non-empty-string,
         *     content: non-empty-string,
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
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

        return new AddNewsDto(title: $denormalizedData['title'], content: $denormalizedData['content']);
    }
}
