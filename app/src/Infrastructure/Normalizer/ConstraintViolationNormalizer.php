<?php

declare(strict_types=1);

namespace App\Infrastructure\Normalizer;

use Spiks\UserInputProcessor\ConstraintViolation\ArrayIsTooLong;
use Spiks\UserInputProcessor\ConstraintViolation\ArrayIsTooShort;
use Spiks\UserInputProcessor\ConstraintViolation\ArrayShouldHaveExactLength;
use Spiks\UserInputProcessor\ConstraintViolation\ConstraintViolationInterface;
use Spiks\UserInputProcessor\ConstraintViolation\NumberIsTooBig;
use Spiks\UserInputProcessor\ConstraintViolation\NumberIsTooSmall;
use Spiks\UserInputProcessor\ConstraintViolation\StringIsTooLong;
use Spiks\UserInputProcessor\ConstraintViolation\StringIsTooShort;
use Spiks\UserInputProcessor\ConstraintViolation\ValueDoesNotMatchRegex;
use Spiks\UserInputProcessor\ConstraintViolation\WrongPropertyType;

readonly class ConstraintViolationNormalizer
{
    public function __construct(private PointerNormalizer $pointerNormalizer)
    {
    }

    public function normalize(ConstraintViolationInterface $constraintViolation): array
    {
        $schema = [
            'type' => $constraintViolation::getType(),
            'description' => $constraintViolation->getDescription(),
            'pointer' => $this->pointerNormalizer->normalizeJsonPointer($constraintViolation->getPointer()),
        ];

        if ($constraintViolation instanceof WrongPropertyType) {
            $schema['allowedTypes'] = $constraintViolation->getAllowedTypes();
            $schema['givenType'] = $constraintViolation->getGivenType();
        }

        if ($constraintViolation instanceof StringIsTooShort) {
            $schema['minLength'] = $constraintViolation->getMinLength();
        }

        if ($constraintViolation instanceof StringIsTooLong) {
            $schema['maxLength'] = $constraintViolation->getMaxLength();
        }

        if ($constraintViolation instanceof NumberIsTooSmall) {
            $schema['min'] = $constraintViolation->getMin();
        }

        if ($constraintViolation instanceof NumberIsTooBig) {
            $schema['max'] = $constraintViolation->getMax();
        }

        if ($constraintViolation instanceof ValueDoesNotMatchRegex) {
            $schema['regex'] = $constraintViolation->getRegex();
        }

        if ($constraintViolation instanceof ArrayIsTooShort) {
            $schema['minLength'] = $constraintViolation->getMinLength();
        }

        if ($constraintViolation instanceof ArrayIsTooLong) {
            $schema['maxLength'] = $constraintViolation->getMaxLength();
        }

        if ($constraintViolation instanceof ArrayShouldHaveExactLength) {
            $schema['length'] = $constraintViolation->getLength();
        }

        return $schema;
    }

    /**
     * @psalm-param list<ConstraintViolationInterface> $constraintViolations
     *
     * @psalm-return array
     */
    public function normalizeCollection(array $constraintViolations): array
    {
        return array_map(
            fn(ConstraintViolationInterface $constraintViolation): array => $this->normalize($constraintViolation),
            $constraintViolations,
        );
    }
}
