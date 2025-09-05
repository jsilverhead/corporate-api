import { commonOperation } from '../path';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { EntityNotFoundApiProblem, ToDateCannotBeLessThanFromDateApiProblem } from '../apiProblem/common';
import {
  ApproveVacationRequestSchema,
  CreateVacationRequestSchema,
  CreateVacationResponseSchema,
  DeleteVacationRequestSchema,
  ListVacationsQueryParameters,
  ListVacationsResponseSchema,
  UpdateVacationRequestSchema,
} from '../schema/vacation';
import {
  CannotDeleteSpentVacationApiProblem,
  CanNotUpdateApprovedVacationApiProblem,
  FromDateCanNotBeLessThatFourteenDaysFromNowApiProblem,
  VacationCanNotBeInThePastApiProblem,
} from '../apiProblem/vacation';

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

commonOperation.post({
  title: 'Одобрение отпуска',
  tag: VacationTag,
  isImplemented: true,
  operationId: 'approveVacation',
  requestSchema: ApproveVacationRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem],
});

commonOperation.post({
  title: 'Обновление отпуска',
  tag: VacationTag,
  isImplemented: true,
  operationId: 'updateVacation',
  requestSchema: UpdateVacationRequestSchema,
  errorSchemas: [
    EntityNotFoundApiProblem,
    VacationCanNotBeInThePastApiProblem,
    FromDateCanNotBeLessThatFourteenDaysFromNowApiProblem,
    CanNotUpdateApprovedVacationApiProblem,
  ],
});

commonOperation.post({
  title: 'Удаление отпуска',
  tag: VacationTag,
  isImplemented: true,
  operationId: 'deleteVacation',
  requestSchema: DeleteVacationRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem, CannotDeleteSpentVacationApiProblem],
});
