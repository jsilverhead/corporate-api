import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import { CreateDepartmentRequestSchema, CreateDepartmentResponseSchema } from '../schema/department';
import { DepartmentWithThisNameAlreadyExistsApiProblem } from '../apiProblem/department';

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
