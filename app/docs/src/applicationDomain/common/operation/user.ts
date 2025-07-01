import { commonOperation } from '../path';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { CreateUserRequestSchema, CreateUserResponseSchema } from '../schema/user';
import { UserWithThisEmailAlreadyExistsApiProblem } from '../apiProblem/user';

export const UserTag: Tag = {
  name: 'Пользователи',
  description: 'Пользователи.',
};

commonOperation.post({
  title: 'Создание пользователя',
  tag: UserTag,
  isImplemented: true,
  operationId: '/createUser',
  requestSchema: CreateUserRequestSchema,
  responseSchema: CreateUserResponseSchema,
  errorSchemas: [UserWithThisEmailAlreadyExistsApiProblem],
});
