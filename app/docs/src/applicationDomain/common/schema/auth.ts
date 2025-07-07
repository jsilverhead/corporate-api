import { ref } from '../../../utils/ref';
import { objectSchema, stringSchema } from '../../../utils/schemaFactory';
import { DateTime } from '../../../schema/common';
import { Email, Password, UserId, UserRole } from './user';

export const AccessToken = ref.schema(
  'AccessToken',
  stringSchema({
    description: 'JWT Токен доступа',
    examples: [
      'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJDb3Jwb3JhdGUgQVBJIiwiaWF0IjoxNzUxNDYyMDk2LCJleHAiOjE3NTE0NjI5OTYsInVzZXJJZCI6ImU4YTFiMGQ0LTI1NWUtNGJkZS1hZWY3LWVkZTU5MmY1NTkyYyIsInNhbHQiOiI2ODY1MzBkMDA1N2VmNi43NDUzNzc2MCJ9.fAhS0EhZCeJGmeLzVReB6Ce8Q14eqFY8Fw-x1SkpJGU',
    ],
    minLength: 1,
  }),
);

export const RefreshToken = ref.schema(
  'RefreshToken',
  stringSchema({
    description: 'JWT Токен обновления',
    examples: [
      'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJDb3Jwb3JhdGUgQVBJIiwiaWF0IjoxNzUxNDYyMDk2LCJleHAiOjE3NTE0NjI5OTYsInVzZXJJZCI6ImU4YTFiMGQ0LTI1NWUtNGJkZS1hZWY3LWVkZTU5MmY1NTkyYyIsInNhbHQiOiI2ODY1MzBkMDA1N2VmNi43NDUzNzc2MCJ9.fAhS0EhZCeJGmeLzVReB6Ce8Q14eqFY8Fw-x1SkpJGU',
    ],
    minLength: 1,
  }),
);

export const TokenExpiresAt = { ...DateTime, description: 'Время жизни токена' };

export const IssueTokenByEmailAndPasswordRequestSchema = ref.schema(
  'IssueTokenByEmailAndPasswordRequestSchema',
  objectSchema({
    description: 'Данные для получение accessToken',
    properties: {
      email: Email,
      password: Password,
    },
  }),
);

export const IssueTokenByEmailAndPasswordResponseSchema = ref.schema(
  'IssueTokenByEmailAndPasswordResponseSchema',
  objectSchema({
    description: 'Пара токенов и время их жизни',
    properties: {
      userId: UserId,
      accessToken: AccessToken,
      refreshToken: RefreshToken,
      accessTokenExpiresAt: TokenExpiresAt,
      refreshTokenExpiresAt: TokenExpiresAt,
      role: UserRole,
    },
  }),
);

export const RefreshAccessTokenRequestSchema = ref.schema(
  'RefreshAccessTokenRequestSchema',
  objectSchema({
    description: 'Данные для обновления токена',
    properties: {
      refreshToken: RefreshToken,
    },
  }),
);

export const RefreshAccessTokenResponseSchema = ref.schema(
  'RefreshAccessTokenResponseSchema',
  objectSchema({
    description: 'Данные обновлённого токена',
    properties: {
      accessToken: AccessToken,
      refreshToken: RefreshToken,
      accessTokenExpiresAt: TokenExpiresAt,
      refreshTokenExpiresAt: TokenExpiresAt,
    },
  }),
);
