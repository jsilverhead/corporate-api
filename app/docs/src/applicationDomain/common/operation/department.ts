import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import {
  AddEmployeeRequestSchema,
  AddSupervisorRequestSchema,
  CreateDepartmentRequestSchema,
  CreateDepartmentResponseSchema,
  GetDepartmentIdParam,
  GetDepartmentResponseSchema,
  ListDepartmentResponseSchema,
  ListDepartmentsParams,
  RemoveSupervisorRequestSchema,
} from '../schema/department';
import {
  DepartmentWithThisNameAlreadyExistsApiProblem,
  UserAlreadyInTheDepartmentApiProblem,
  UserAlreadySupervisingThisDepartmentApiProblem,
  UserDoNotSupervisingThisDepartmentApiProblem,
} from '../apiProblem/department';
import { EntityNotFoundApiProblem } from '../apiProblem/common';

export const DepartmentTag: Tag = {
  description: 'Отделы',
  name: 'Отделы',
};

commonOperation.post({
  tag: DepartmentTag,
  title: 'Создание отдела',
  isImplemented: true,
  operationId: 'createDepartment',
  requestSchema: CreateDepartmentRequestSchema,
  responseSchema: CreateDepartmentResponseSchema,
  errorSchemas: [DepartmentWithThisNameAlreadyExistsApiProblem],
});

commonOperation.get({
  tag: DepartmentTag,
  title: 'Получение отдела',
  isImplemented: true,
  operationId: 'getDepartment',
  parameters: [GetDepartmentIdParam],
  responseSchema: GetDepartmentResponseSchema,
  errorSchemas: [EntityNotFoundApiProblem],
});

commonOperation.get({
  tag: DepartmentTag,
  title: 'Полуение списка отделов',
  isImplemented: true,
  operationId: 'listDepartments',
  parameters: ListDepartmentsParams,
  responseSchema: ListDepartmentResponseSchema,
});

commonOperation.post({
  tag: DepartmentTag,
  title: 'Добавление куратора',
  isImplemented: true,
  operationId: 'addSupervisor',
  requestSchema: AddSupervisorRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem, UserAlreadySupervisingThisDepartmentApiProblem],
});

commonOperation.post({
  tag: DepartmentTag,
  title: 'Удаление куратора',
  isImplemented: true,
  operationId: 'removeSupervisor',
  requestSchema: RemoveSupervisorRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem, UserDoNotSupervisingThisDepartmentApiProblem],
});

commonOperation.post({
  tag: DepartmentTag,
  title: 'Добавление сотрудника',
  isImplemented: true,
  operationId: 'addEmployee',
  requestSchema: AddEmployeeRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem, UserAlreadyInTheDepartmentApiProblem],
});
