import { ref } from '../../../utils/ref';
import { objectSchema, stringSchema } from '../../../utils/schemaFactory';
import { enumeration } from '../../../utils/enum';
import { Uuid } from '../../../schema/common';

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
    examples: ['olego@spiks.ru'],
    minLength: 1,
    maxLength: 255,
  }),
);

export const Password = ref.schema(
  'Password',
  stringSchema({
    description: 'Пароль',
    examples: ['Spiks123'],
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
