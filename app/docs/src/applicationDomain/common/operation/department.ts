import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import {
  CreateDepartmentRequestSchema,
  CreateDepartmentResponseSchema,
  GetDepartmentIdParam,
  GetDepartmentResponseSchema,
} from '../schema/department';
import { DepartmentWithThisNameAlreadyExistsApiProblem } from '../apiProblem/department';
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
