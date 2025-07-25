import { commonOperation } from '../path';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { ToDateCannotBeLessThanFromDateApiProblem } from '../apiProblem/common';
import {
  CreateVacationRequestSchema,
  CreateVacationResponseSchema,
  UpdateVacationRequestSchema,
} from '../schema/vacation';
import {
  AnotherVacationExistsInsideTheChosenPeriodApiProblem,
  CanNotUpdateApprovedVacationApiProblem,
  FromDateCanNotBeInThePastApiProblem,
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
  errorSchemas: [
    ToDateCannotBeLessThanFromDateApiProblem,
    FromDateCanNotBeInThePastApiProblem,
    AnotherVacationExistsInsideTheChosenPeriodApiProblem,
  ],
});

commonOperation.post({
  title: 'Обновление отпуска',
  tag: VacationTag,
  isImplemented: true,
  operationId: 'updateVacation',
  requestSchema: UpdateVacationRequestSchema,
  errorSchemas: [
    ToDateCannotBeLessThanFromDateApiProblem,
    CanNotUpdateApprovedVacationApiProblem,
    FromDateCanNotBeInThePastApiProblem,
    AnotherVacationExistsInsideTheChosenPeriodApiProblem,
  ],
});
