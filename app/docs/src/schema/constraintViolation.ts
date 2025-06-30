import type { Schema, SchemaObject } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { ref } from '../utils/ref';
import { integerSchema, stringSchema, objectSchema, arraySchema } from '../utils/schemaFactory';
import { enumeration } from '../utils/enum';

const constraintViolation = (
  violationType: string,
  additionalProperties: { [key: string]: Schema } = {},
): SchemaObject =>
  objectSchema({
    description: `Ошибка валидации типа \`${violationType}\`.`,
    properties: {
      type: {
        description: 'Тип ошибки валидации.',
        const: violationType,
      },
      description: stringSchema({
        description: 'Описание ошибки валидации для разработчиков.',
        minLength: 1,
        maxLength: undefined,
        examples: [],
      }),
      pointer: stringSchema({
        format: 'json-pointer',
        examples: ['/coordinates/lan'],
        description: 'Указатель на поле с ошибкой в формате JSON Pointer.',
        minLength: 1,
        maxLength: undefined,
        externalDocs: {
          url: 'https://datatracker.ietf.org/doc/html/rfc6901',
          description: 'RFC6901. JavaScript Object Notation (JSON) Pointer.',
        },
      }),
      ...additionalProperties,
    },
  });

const EnumValueIsNotAllowedConstraintViolation = ref.schema(
  'EnumValueIsNotAllowedConstraintViolation',
  constraintViolation('enum_value_is_not_allowed', {
    allowedValues: arraySchema({
      description: 'Список допустимых значений.',
      minItems: 2,
      uniqueItems: true,
      items: stringSchema({
        description: 'Допустимое значение.',
        minLength: 1,
        maxLength: undefined,
        examples: ['correct'],
      }),
    }),
  }),
);

const ResourceIsNotAvailableConstraintViolation = ref.schema(
  'ResourceIsNotAvailableConstraintViolation',
  constraintViolation('resource_is_not_available'),
);

const ValueIsNotValidConstraintViolation = ref.schema(
  'ValueIsNotValidConstraintViolation',
  constraintViolation('value_is_not_valid'),
);

const ArrayIsTooLongConstraintViolation = ref.schema(
  'ArrayIsTooLongConstraintViolation',
  constraintViolation('array_is_too_long', {
    maxLength: integerSchema({
      minimum: 0,
      maximum: undefined,
      description: 'Максимальная длина массива.',
      examples: [10],
    }),
  }),
);

const ArrayIsTooShortConstraintViolation = ref.schema(
  'ArrayIsTooShortConstraintViolation',
  constraintViolation('array_is_too_short', {
    minLength: integerSchema({
      minimum: 0,
      maximum: undefined,
      description: 'Минимальная длина массива.',
      examples: [1],
    }),
  }),
);

const ArrayShouldHaveExactLengthConstraintViolation = ref.schema(
  'ArrayShouldHaveExactLengthConstraintViolation',
  constraintViolation('array_should_have_exact_length', {
    length: integerSchema({
      minimum: 0,
      maximum: undefined,
      description: 'Длина массива.',
      examples: [10],
    }),
  }),
);

const MandatoryFieldMissingConstraintViolation = ref.schema(
  'MandatoryFieldMissingConstraintViolation',
  constraintViolation('mandatory_field_missing'),
);

const CodeIsInvalidConstraintViolation = ref.schema(
  'CodeIsInvalidConstraintViolation',
  constraintViolation('code_is_invalid'),
);

const NumberIsTooBigConstraintViolation = ref.schema(
  'NumberIsTooBigConstraintViolation',
  constraintViolation('number_is_too_big', {
    max: integerSchema({
      description: 'Максимальное значение числа.',
      minimum: 1,
      maximum: undefined,
      examples: [50],
    }),
  }),
);

const NumberIsTooSmallConstraintViolation = ref.schema(
  'NumberIsTooSmallConstraintViolation',
  constraintViolation('number_is_too_small', {
    min: integerSchema({
      minimum: 0,
      maximum: undefined,
      examples: [1],
      description: 'Минимальное значение числа.',
    }),
  }),
);

const StringIsTooLongConstraintViolation = ref.schema(
  'StringIsTooLongConstraintViolation',
  constraintViolation('string_is_too_long', {
    maxLength: integerSchema({
      minimum: 1,
      maximum: undefined,
      examples: [10],
      description: 'Максимальная длина строки.',
    }),
  }),
);

const StringIsTooShortConstraintViolation = ref.schema(
  'StringIsTooShortConstraintViolation',
  constraintViolation('string_is_too_short', {
    minLength: integerSchema({
      minimum: 0,
      maximum: undefined,
      examples: [1],
      description: 'Минимальная длина строки.',
    }),
  }),
);

const ValueDoesNotMatchRegexConstraintViolation = ref.schema(
  'ValueDoesNotMatchRegexConstraintViolation',
  constraintViolation('value_does_not_match_regex', {
    regex: stringSchema({
      description: 'Регулярное выражение, которому должна соответствовать строка.',
      minLength: 0,
      maxLength: undefined,
      examples: ['^[a-zA-Z]{10}$'],
    }),
  }),
);

const TooManyCodeRequestsConstraintViolation = ref.schema(
  'TooManyCodeRequestsConstraintViolation',
  constraintViolation('too_many_code_requests', {
    retryIn: integerSchema({
      minimum: 0,
      maximum: undefined,
      examples: [3],
      description: 'Количество секунд, через которое можно повторить запрос.',
    }),
  }),
);

const TooManyCodeUseAttemptsConstraintViolation = ref.schema(
  'TooManyCodeUseAttemptsConstraintViolation',
  constraintViolation('too_many_code_use_attempts', {
    retryIn: integerSchema({
      minimum: 0,
      maximum: undefined,
      examples: [1],
      description: 'Количество секунд, через которое можно повторить запрос.',
    }),
  }),
);

const ValueShouldNotBeNullConstraintViolation = ref.schema(
  'ValueShouldNotBeNullConstraintViolation',
  constraintViolation('value_should_not_be_null'),
);

const WrongDiscriminatorValueConstraintViolation = ref.schema(
  'WrongDiscriminatorValueConstraintViolation',
  constraintViolation('wrong_discriminator_value', {
    allowedValues: arraySchema({
      description: 'Список допустимых значений.',
      minItems: 2,
      uniqueItems: true,
      items: stringSchema({
        description: 'Допустимое значение.',
        minLength: 1,
        maxLength: undefined,
        examples: ['example'],
      }),
    }),
  }),
);

const JsonType = ref.schema(
  'JsonType',
  enumeration({
    description: 'Тип JSON.',
    enumsWithDescriptions: {
      array: 'Массив.',
      boolean: 'Булев.',
      float: 'Число с плавающей точкой.',
      integer: 'Целое число.',
      null: 'Нулл.',
      object: 'Объект.',
      string: 'Строка.',
    },
  }),
);

const WrongPropertyTypeConstraintViolation = ref.schema(
  'WrongPropertyTypeConstraintViolation',
  constraintViolation('wrong_property_type', {
    givenType: JsonType,
    allowedTypes: arraySchema({
      description: 'Список допустимых типов.',
      minItems: 1,
      uniqueItems: true,
      items: JsonType,
    }),
  }),
);

const PhoneIsNotValidConstraintViolation = ref.schema(
  'PhoneIsNotValidConstraintViolation',
  constraintViolation('phone_is_not_valid'),
);

const UuidIsNotValidConstraintViolation = ref.schema(
  'UuidIsNotValidConstraintViolation',
  constraintViolation('uuid_is_not_valid'),
);

export const constraintViolations = [
  EnumValueIsNotAllowedConstraintViolation,
  ResourceIsNotAvailableConstraintViolation,
  ValueIsNotValidConstraintViolation,
  ArrayIsTooLongConstraintViolation,
  ArrayIsTooShortConstraintViolation,
  ArrayShouldHaveExactLengthConstraintViolation,
  MandatoryFieldMissingConstraintViolation,
  NumberIsTooBigConstraintViolation,
  NumberIsTooSmallConstraintViolation,
  StringIsTooLongConstraintViolation,
  StringIsTooShortConstraintViolation,
  ValueDoesNotMatchRegexConstraintViolation,
  ValueShouldNotBeNullConstraintViolation,
  WrongDiscriminatorValueConstraintViolation,
  WrongPropertyTypeConstraintViolation,
  CodeIsInvalidConstraintViolation,
  TooManyCodeRequestsConstraintViolation,
  TooManyCodeUseAttemptsConstraintViolation,
  PhoneIsNotValidConstraintViolation,
  UuidIsNotValidConstraintViolation,
];
