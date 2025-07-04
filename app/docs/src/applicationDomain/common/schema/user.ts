import { ref } from '../../../utils/ref';
import { objectSchema, stringSchema } from '../../../utils/schemaFactory';
import { enumeration } from '../../../utils/enum';
import { Date, Uuid } from '../../../schema/common';
import { collectionWithItemsAmount } from '../../../schema/collection';
import { PaginationParameters } from '../../../schema/pagination';
import { nullable } from '../../../utils/nullable';

export const UserName = ref.schema(
  'UserName',
  stringSchema({
    description: 'Имя пользователя',
    examples: ['Олегов Олег'],
    minLength: 3,
    maxLength: 255,
  }),
);

export const Email = ref.schema(
  'Email',
  stringSchema({
    description: 'Email пользователя',
    examples: ['olego@company.ru'],
    minLength: 1,
    maxLength: 255,
  }),
);

export const Password = ref.schema(
  'Password',
  stringSchema({
    description: 'Пароль',
    examples: ['Password123'],
    minLength: 8,
    maxLength: 255,
  }),
);

export const UserRole = ref.schema(
  'UserRole',
  enumeration({
    description: 'Роль пользователя',
    enumsWithDescriptions: {
      user: 'Пользователь',
      superuser: 'Суперпользователь',
    },
  }),
);

export const UserId = { ...Uuid, description: 'Идентификатор пользователя' };

export const UserBirthDate = { ...Date, description: 'День рождения пользователя' };

export const CreateUserRequestSchema = ref.schema(
  'CreateUserRequestSchema',
  objectSchema({
    description: 'Данные для создания пользователя',
    properties: {
      name: UserName,
      email: Email,
      password: Password,
      role: UserRole,
    },
  }),
);

export const CreateUserResponseSchema = ref.schema(
  'CreateUserResponseSchema',
  objectSchema({
    description: 'ID созданного пользователя',
    properties: {
      id: UserId,
    },
  }),
);

export const DeleteUserRequestSchema = ref.schema(
  'DeleteUserRequestSchema',
  objectSchema({
    description: 'Данные для удаления пользователя',
    properties: {
      id: UserId,
    },
  }),
);

const ListUsersItemSchema = ref.schema(
  'ListUsersItemSchema',
  objectSchema({
    description: 'Данные пользователя',
    properties: {
      id: UserId,
      name: UserName,
      email: Email,
      role: UserRole,
    },
  }),
);

export const ListUsersResponseItemsSchema = collectionWithItemsAmount(
  'ListUsersResponseItemsSchema',
  ListUsersItemSchema,
);

export const UpdateUserRequestSchema = ref.schema(
  'UpdateUserRequestSchema',
  objectSchema({
    description: 'Данные для обновления пользователя',
    properties: {
      userId: UserId,
      name: UserName,
      role: UserRole,
    },
  }),
);

const ListUsersSearchParam = ref.parameter('ListUsersSearchParam', {
  in: 'query',
  name: 'filter[search]',
  schema: stringSchema({
    description: 'Поисковые слова',
    examples: ['олег'],
  }),
});

export const ListUsersParameters = [...PaginationParameters, ListUsersSearchParam];

export const GetUserIdParameter = ref.parameter('GetUserIdParameter', {
  in: 'query',
  name: 'id',
  schema: UserId,
  required: true,
});

export const GetUserResponseSchema = ref.schema(
  'GetUserResponseSchema',
  objectSchema({
    description: 'Данные пользователя',
    properties: {
      id: UserId,
      name: UserName,
      email: Email,
      role: UserRole,
      birthDate: nullable(UserBirthDate),
    },
  }),
);
