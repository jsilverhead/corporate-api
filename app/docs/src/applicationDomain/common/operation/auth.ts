import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import { EntityNotFoundApiProblem } from '../apiProblem/common';
import { PasswordIsInvalidApiProblem } from '../apiProblem/auth';
import { IssueTokenByEmailAndPasswordRequestSchema, IssueTokenByEmailAndPasswordResponseSchema } from '../schema/auth';

export const AuthTag: Tag = {
  name: 'Аутентификация',
  description: 'Аутентификация',
};

commonOperation.post({
  title: 'Получить accessToken по email и паролю',
  tag: AuthTag,
  isImplemented: true,
  operationId: '/issueTokenByEmailAndPassword',
  requestSchema: IssueTokenByEmailAndPasswordRequestSchema,
  responseSchema: IssueTokenByEmailAndPasswordResponseSchema,
  errorSchemas: [EntityNotFoundApiProblem, PasswordIsInvalidApiProblem],
});
