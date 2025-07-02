import { commonOperation } from '../path';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import {
  CreateUserRequestSchema,
  CreateUserResponseSchema,
  DeleteUserRequestSchema,
  ListUsersResponseItemsSchema,
} from '../schema/user';
import { UserAlreadyDeletedApiProblem, UserWithThisEmailAlreadyExistsApiProblem } from '../apiProblem/user';
import { EntityNotFoundApiProblem } from '../apiProblem/common';
import { PaginationParameters } from '../../../schema/pagination';

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

commonOperation.post({
  title: 'Удаление пользователя',
  tag: UserTag,
  isImplemented: true,
  operationId: '/deleteUser',
  requestSchema: DeleteUserRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem, UserAlreadyDeletedApiProblem],
});

commonOperation.get({
  title: 'Список пользователей',
  tag: UserTag,
  isImplemented: true,
  operationId: '/listUsers',
  parameters: [...PaginationParameters],
  responseSchema: ListUsersResponseItemsSchema,
});
