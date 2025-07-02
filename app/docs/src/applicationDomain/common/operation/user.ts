import { commonOperation } from '../path';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import {
  CreateUserRequestSchema,
  CreateUserResponseSchema,
  DeleteUserRequestSchema,
  GetUserIdParameter,
  GetUserResponseSchema,
  ListUsersParameters,
  ListUsersResponseItemsSchema,
  UpdateUserRequestSchema,
} from '../schema/user';
import { UserAlreadyDeletedApiProblem, UserWithThisEmailAlreadyExistsApiProblem } from '../apiProblem/user';
import { EntityNotFoundApiProblem } from '../apiProblem/common';

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
  parameters: ListUsersParameters,
  responseSchema: ListUsersResponseItemsSchema,
});

commonOperation.post({
  title: 'Обновить пользователя',
  tag: UserTag,
  isImplemented: true,
  operationId: '/updateUser',
  requestSchema: UpdateUserRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem],
});

commonOperation.get({
  title: 'Получить пользователя',
  tag: UserTag,
  isImplemented: true,
  operationId: '/getUser',
  parameters: [GetUserIdParameter],
  responseSchema: GetUserResponseSchema,
  errorSchemas: [EntityNotFoundApiProblem],
});
