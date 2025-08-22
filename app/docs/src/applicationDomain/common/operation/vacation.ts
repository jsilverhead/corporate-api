import { commonOperation } from '../path';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { ToDateCannotBeLessThanFromDateApiProblem } from '../apiProblem/common';
import {
  CreateVacationRequestSchema,
  CreateVacationResponseSchema,
  ListVacationsQueryParameters,
  ListVacationsResponseSchema,
} from '../schema/vacation';

export const VacationTag: Tag = {
  description: 'Отпуска',
  name: 'Отпуска',
};

commonOperation.post({
  title: 'Создание отпуска',
  tag: VacationTag,
  isImplemented: true,
  operationId: 'createVacation',
  requestSchema: CreateVacationRequestSchema,
  responseSchema: CreateVacationResponseSchema,
  errorSchemas: [ToDateCannotBeLessThanFromDateApiProblem],
});

commonOperation.get({
  title: 'Получение списка отпусков',
  tag: VacationTag,
  isImplemented: true,
  operationId: 'listVacations',
  parameters: ListVacationsQueryParameters,
  responseSchema: ListVacationsResponseSchema,
});
