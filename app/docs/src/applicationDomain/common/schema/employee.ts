import { ref } from '../../../utils/ref';
import { objectSchema, stringSchema } from '../../../utils/schemaFactory';
import { enumeration } from '../../../utils/enum';
import { Date, Uuid } from '../../../schema/common';
import { collectionWithItemsAmount } from '../../../schema/collection';
import { PaginationParameters } from '../../../schema/pagination';
import { nullable } from '../../../utils/nullable';
import { DepartmentId, DepartmentName } from './department';

export const EmployeeName = ref.schema(
  'EmployeeName',
  stringSchema({
    description: 'Имя сотрудника',
    examples: ['Олегов Олег'],
    minLength: 3,
    maxLength: 255,
  }),
);

export const Email = ref.schema(
  'Email',
  stringSchema({
    description: 'Email сотрудника',
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

export const EmployeeRole = ref.schema(
  'EmployeeRole',
  enumeration({
    description: 'Роль сотрудника в системе',
    enumsWithDescriptions: {
      user: 'Пользователь',
      superuser: 'Суперпользователь',
    },
  }),
);

export const EmployeeId = { ...Uuid, description: 'Идентификатор сотрудника' };

export const EmployeeBirthDate = { ...Date, description: 'День рождения пользователя' };

export const CreateEmployeeRequestSchema = ref.schema(
  'CreateEmployeeRequestSchema',
  objectSchema({
    description: 'Данные для создания сотрудника',
    properties: {
      name: EmployeeName,
      email: Email,
      password: Password,
      role: EmployeeRole,
      birthDate: nullable(EmployeeBirthDate),
      departmentId: nullable(DepartmentId),
      supervisingId: nullable(DepartmentId),
    },
  }),
);

export const CreateEmployeeResponseSchema = ref.schema(
  'CreateEmployeeResponseSchema',
  objectSchema({
    description: 'ID созданного сотрудника',
    properties: {
      id: EmployeeId,
    },
  }),
);

export const DeleteEmployeeRequestSchema = ref.schema(
  'DeleteEmployeeRequestSchema',
  objectSchema({
    description: 'Данные для удаления сотрудника',
    properties: {
      id: EmployeeId,
    },
  }),
);

const ListEmployeesItemSchema = ref.schema(
  'ListEmployeesItemSchema',
  objectSchema({
    description: 'Данные сотрудника',
    properties: {
      id: EmployeeId,
      name: EmployeeName,
      email: Email,
      role: EmployeeRole,
      department: DepartmentName,
    },
  }),
);

export const ListEmployeesResponseItemsSchema = collectionWithItemsAmount(
  'ListEmployeesResponseItemsSchema',
  ListEmployeesItemSchema,
);

export const UpdateEmployeeRequestSchema = ref.schema(
  'UpdateEmployeeRequestSchema',
  objectSchema({
    description: 'Данные для обновления сотрудника',
    properties: {
      employeeId: EmployeeId,
      name: EmployeeName,
      role: EmployeeRole,
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

export const GetEmployeeIdParameter = ref.parameter('GetEmployeeIdParameter', {
  in: 'query',
  name: 'id',
  schema: EmployeeId,
  required: true,
});

export const EmployeeDepartment = ref.schema(
  'EmployeeDepartment',
  objectSchema({
    description: 'Информация об отделе',
    properties: {
      id: DepartmentId,
      name: DepartmentName,
    },
  }),
);

export const EmployeeSupervising = ref.schema(
  'EmployeeSupervising',
  objectSchema({
    description: 'Информация об управляемом отделе',
    properties: {
      id: DepartmentId,
      name: DepartmentName,
    },
  }),
);

export const GetEmployeeResponseSchema = ref.schema(
  'GetEmployeeResponseSchema',
  objectSchema({
    description: 'Данные сотрудника',
    properties: {
      id: EmployeeId,
      name: EmployeeName,
      email: Email,
      role: EmployeeRole,
      birthDate: nullable(EmployeeBirthDate),
      department: nullable(EmployeeDepartment),
      supervising: nullable(EmployeeSupervising),
    },
  }),
);
