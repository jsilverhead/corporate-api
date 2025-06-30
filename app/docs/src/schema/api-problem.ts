import { ref } from '../utils/ref';
import type { SchemaObject, Schema } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { integerSchema, arraySchema, objectSchema, stringSchema } from '../utils/schemaFactory';
import { constraintViolations } from './constraintViolation';
import { constant } from '../utils/constant';

export const apiProblem = (params: {
  additionalProperties?: { [key: string]: Schema };
  description: string;
  type: string;
}): SchemaObject =>
  objectSchema({
    description: params.description,
    additionalProperties: false,
    externalDocs: {
      url: 'https://datatracker.ietf.org/doc/html/rfc7807',
      description: 'RFC7807. Problem Details for HTTP APIs.',
    },
    properties: {
      type: constant({
        description: `Идентификатор ошибки API.\n${params.description}`,
        value: params.type,
      }),
      detail: stringSchema({
        description: 'Описание ошибки.',
        minLength: 1,
        maxLength: undefined,
        examples: [params.description],
      }),
      ...params.additionalProperties,
    },
  });

export const ValidationErrorApiProblem = ref.schema(
  'ValidationErrorApiProblem',
  apiProblem({
    type: 'validation_error',
    description: 'Ошибка возвращается в случае, если некоторые поля в запросе не валидны.',
    additionalProperties: {
      violations: arraySchema({
        description: 'Ошибки валидации.',
        minItems: 1,
        uniqueItems: true,
        items: {
          oneOf: constraintViolations,
        },
      }),
    },
  }),
);

export const UnparsableRequestApiProblem = ref.schema(
  'UnparsableRequestApiProblem',
  apiProblem({
    description: 'Ошибка возвращается в случае, если запрос содержит невалидный json.',
    type: 'unparsable_request',
  }),
);

export const AuthorizationHeaderMissingApiProblem = ref.schema(
  'AuthorizationHeaderMissingApiProblem',
  apiProblem({
    description: 'Ошибка возвращается в случае, если в запросе не указан `Authorization` заголовок.',
    type: 'authorization_header_missing',
  }),
);

export const InvalidAuthorizationHeaderApiProblem = ref.schema(
  'InvalidAuthorizationHeaderApiProblem',
  apiProblem({
    description: 'Ошибка возвращается в случае, если в запросе указан невалидный `Authorization` заголовок.',
    type: 'invalid_authorization_header',
  }),
);

export const UnknownAccessTokenApiProblem = ref.schema(
  'UnknownAccessTokenApiProblem',
  apiProblem({
    description: 'Access token в запросе несопоставим не с одним пользователем.',
    type: 'unknown_access_token',
  }),
);

export const ExpiredAccessTokenApiProblem = ref.schema(
  'ExpiredAccessTokenApiProblem',
  apiProblem({
    description: 'Access token, переданный в запросе, истек.',
    type: 'expired_access_token',
  }),
);

export const ProfileDoesNotExistApiProblem = ref.schema(
  'ProfileDoesNotExistApiProblem',
  apiProblem({
    description: 'Профиль не существует.',
    type: 'profile_does_not_exists',
  }),
);

export const InternalServerErrorApiProblem = ref.schema(
  'InternalServerErrorApiProblem',
  apiProblem({
    description: 'Ошибка возвращается в случае, если на сервере произошла непредвиденная проблема.',
    type: 'internal_server_error',
  }),
);

export const ServiceUnavailableApiProblem = ref.schema(
  'ServiceUnavailableApiProblem',
  apiProblem({
    description:
      'Ваш запрос, вероятно, был правильным, но какая-то внутренняя служба API отказалась его обрабатывать ' +
      '(возможно, она не работает или неправильно настроена). Вы можете попробовать отправить этот запрос позже.',
    type: 'service_unavailable',
  }),
);

export const TooManyRequestsApiProblem = ref.schema(
  'TooManyRequestsApiProblem',
  apiProblem({
    type: 'too_many_requests',
    description: 'Клиентское приложение или пользователь отправили слишком много запросов.',
    additionalProperties: {
      retryAfter: integerSchema({
        description: 'Количество секунд, через которое можно повторить запрос.',
        minimum: 0,
        maximum: undefined,
        examples: [3],
      }),
    },
  }),
);

export const ConcurrentAccessToEntityApiProblem = ref.schema(
  'ConcurrentAccessToEntityApiProblem',
  apiProblem({
    description: 'Ошибка возвращается в случае одновременного изменения сущности двумя запросами.',
    type: 'concurrent_access_to_entity',
  }),
);
