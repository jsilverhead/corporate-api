import { ref } from '../../../utils/ref';
import { objectSchema, stringSchema } from '../../../utils/schemaFactory';
import { Uuid } from '../../../schema/common';
import { PaginationParameters } from '../../../schema/pagination';
import { collectionWithItemsAmount } from '../../../schema/collection';

export const DepartmentName = ref.schema(
  'DepartmentName',
  stringSchema({
    description: 'Имя отдела',
    minLength: 1,
    maxLength: 255,
    examples: ['Департамент HR'],
  }),
);

export const DepartmentId = { ...Uuid, description: 'ID отдела' };

export const GetDepartmentIdParam = ref.parameter('GetDepartmentIdParam', {
  in: 'query',
  name: 'id',
  required: true,
  schema: DepartmentId,
});

export const GetDepartmentResponseSchema = ref.schema(
  'GetDepartmentResponseSchema',
  objectSchema({
    description: 'Данные отдела',
    properties: {
      id: DepartmentId,
      name: DepartmentName,
    },
  }),
);

export const CreateDepartmentRequestSchema = ref.schema(
  'CreateDepartmentRequestSchema',
  objectSchema({
    description: 'Данные для создания отдела',
    properties: {
      name: DepartmentName,
    },
  }),
);

export const CreateDepartmentResponseSchema = ref.schema(
  'CreateDepartmentResponseSchema',
  objectSchema({
    description: 'Данные созданного отдела',
    properties: {
      id: DepartmentId,
    },
  }),
);

const ListDepartmentSearchParameter = ref.schema(
  'ListDepartmentSearchParameter',
  stringSchema({
    description: 'Строка поиска',
    examples: ['отдел'],
    minLength: 1,
    maxLength: 255,
  }),
);

export const ListDepartmentsSearchParam = ref.parameter('ListDepartmentsSearchParam', {
  in: 'query',
  name: 'filter[search]',
  required: false,
  schema: ListDepartmentSearchParameter,
});

export const ListDepartmentsParams = [...PaginationParameters, ListDepartmentsSearchParam];

const ListDepartmentsItem = ref.schema(
  'ListDepartmentsItem',
  objectSchema({
    description: 'Данные отдела',
    properties: {
      id: DepartmentId,
      name: DepartmentName,
    },
  }),
);

export const ListDepartmentResponseSchema = collectionWithItemsAmount(
  'ListDepartmentResponseSchema',
  ListDepartmentsItem,
);

export const AddSupervisorRequestSchema = ref.schema(
  'AddSupervisorRequestSchema',
  objectSchema({
    description: 'Данные для добавления куратора',
    properties: {
      departmentId: DepartmentId,
      employeeId: Uuid,
    },
  }),
);

export const RemoveSupervisorRequestSchema = ref.schema(
  'RemoveSupervisorRequestSchema',
  objectSchema({
    description: 'Данные для удаления куратора',
    properties: {
      departmentId: DepartmentId,
      employeeId: Uuid,
    },
  }),
);

export const AddEmployeeRequestSchema = ref.schema(
  'AddEmployeeRequestSchema',
  objectSchema({
    description: 'Данные для добавления сотрудника',
    properties: {
      departmentId: DepartmentId,
      employeeId: Uuid,
    },
  }),
);

export const RemoveEmployeeRequestSchema = ref.schema(
  'RemoveEmployeeRequestSchema',
  objectSchema({
    description: 'Данные для удаления сотрудника',
    properties: {
      departmentId: DepartmentId,
      employeeId: Uuid,
    },
  }),
);

export const UpdateDepartmentRequestSchema = ref.schema(
  'UpdateDepartmentRequestSchema',
  objectSchema({
    description: 'Данные для обновления отдела',
    properties: {
      id: DepartmentId,
      name: DepartmentName,
    },
  }),
);

export const DeleteDepartmentRequestSchema = ref.schema(
  'DeleteDepartmentRequestSchema',
  objectSchema({
    description: 'Данные для удаления отдела',
    properties: {
      id: DepartmentId,
    },
  }),
);
