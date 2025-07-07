import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import { EntityNotFoundApiProblem } from '../apiProblem/common';
import {
  ExpiredJwtTokenApiProblem,
  JwtTokenIsInvalidApiProblem,
  PasswordIsInvalidApiProblem,
  UnknownTokenApiProblem,
} from '../apiProblem/auth';
import {
  IssueTokenByEmailAndPasswordRequestSchema,
  IssueTokenByEmailAndPasswordResponseSchema,
  RefreshAccessTokenRequestSchema,
  RefreshAccessTokenResponseSchema,
} from '../schema/auth';

export const AuthTag: Tag = {
  name: 'Аутентификация',
  description: 'Аутентификация',
};

commonOperation.post({
  title: 'Получить AccessToken по email и паролю',
  tag: AuthTag,
  isImplemented: true,
  operationId: 'issueTokenByEmailAndPassword',
  requestSchema: IssueTokenByEmailAndPasswordRequestSchema,
  responseSchema: IssueTokenByEmailAndPasswordResponseSchema,
  errorSchemas: [EntityNotFoundApiProblem, PasswordIsInvalidApiProblem],
});

commonOperation.post({
  title: 'Обновить AccessToken',
  tag: AuthTag,
  isImplemented: true,
  operationId: 'refreshToken',
  requestSchema: RefreshAccessTokenRequestSchema,
  responseSchema: RefreshAccessTokenResponseSchema,
  errorSchemas: [UnknownTokenApiProblem, ExpiredJwtTokenApiProblem, JwtTokenIsInvalidApiProblem],
});
