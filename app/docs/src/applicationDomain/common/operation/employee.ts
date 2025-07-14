import { commonOperation } from '../path';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import {
  CreateEmployeeRequestSchema,
  CreateEmployeeResponseSchema,
  DeleteEmployeeRequestSchema,
  GetEmployeeIdParameter,
  GetEmployeeResponseSchema,
  ListUsersParameters,
  ListEmployeesResponseItemsSchema,
  UpdateEmployeeRequestSchema,
} from '../schema/employee';
import { EmployeeWithThisEmailAlreadyExistsApiProblem, EmployeeAlreadyDeletedApiProblem } from '../apiProblem/employee';
import { EntityNotFoundApiProblem } from '../apiProblem/common';

export const EmployeeTag: Tag = {
  name: 'Сотрудники',
  description: 'Сотрудники.',
};

commonOperation.post({
  title: 'Создание сотрудника',
  tag: EmployeeTag,
  isImplemented: true,
  operationId: 'createEmployee',
  requestSchema: CreateEmployeeRequestSchema,
  responseSchema: CreateEmployeeResponseSchema,
  errorSchemas: [EmployeeWithThisEmailAlreadyExistsApiProblem],
});

commonOperation.post({
  title: 'Удаление сотрудника',
  tag: EmployeeTag,
  isImplemented: true,
  operationId: 'deleteEmployee',
  requestSchema: DeleteEmployeeRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem, EmployeeAlreadyDeletedApiProblem],
});

commonOperation.get({
  title: 'Список сотрудников',
  tag: EmployeeTag,
  isImplemented: true,
  operationId: 'listEmployees',
  parameters: ListUsersParameters,
  responseSchema: ListEmployeesResponseItemsSchema,
});

commonOperation.post({
  title: 'Обновить сотрудника',
  tag: EmployeeTag,
  isImplemented: true,
  operationId: 'updateEmployees',
  requestSchema: UpdateEmployeeRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem],
});

commonOperation.get({
  title: 'Получить сотрудника',
  tag: EmployeeTag,
  isImplemented: true,
  operationId: 'getEmployee',
  parameters: [GetEmployeeIdParameter],
  responseSchema: GetEmployeeResponseSchema,
  errorSchemas: [EntityNotFoundApiProblem],
});
