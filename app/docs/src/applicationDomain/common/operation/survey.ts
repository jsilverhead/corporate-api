import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import {
  ApplySurveyRequestSchema,
  CreateSurveyRequestSchema,
  CreateSurveyResponseSchema,
  CreateSurveyTemplateRequestSchema,
  CreateSurveyTemplateResponseSchema,
  DeleteSurveyTemplateRequestSchema,
  ListSurveyResponseSchema,
  ListSurveysParameters,
  ListSurveyTemplatesResponseSchema,
} from '../schema/survey';
import { EntitiesNotFoundByIdsApiProblem, EntityNotFoundApiProblem } from '../apiProblem/common';
import { EmployeeAlreadyHasASurveyApiProblem } from '../apiProblem/survey';
import { PaginationParameters } from '../../../schema/pagination';

export const SurveyTag: Tag = {
  name: 'Анкеты',
  description: 'Анкеты',
};

commonOperation.post({
  title: 'Создание шаблона анкеты',
  tag: SurveyTag,
  isImplemented: true,
  operationId: 'createSurveyTemplate',
  requestSchema: CreateSurveyTemplateRequestSchema,
  responseSchema: CreateSurveyTemplateResponseSchema,
});

commonOperation.post({
  title: 'Создание анкеты',
  tag: SurveyTag,
  isImplemented: true,
  operationId: 'createSurvey',
  requestSchema: CreateSurveyRequestSchema,
  responseSchema: CreateSurveyResponseSchema,
  errorSchemas: [EntityNotFoundApiProblem, EmployeeAlreadyHasASurveyApiProblem],
});

commonOperation.get({
  title: 'Получение списка шаблонов анкет',
  tag: SurveyTag,
  isImplemented: true,
  operationId: 'listSurveyTemplates',
  parameters: [...PaginationParameters],
  responseSchema: ListSurveyTemplatesResponseSchema,
});

commonOperation.post({
  title: 'Удаление шаблона анкеты',
  tag: SurveyTag,
  isImplemented: true,
  operationId: 'deleteSurveyTemplate',
  requestSchema: DeleteSurveyTemplateRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem],
});

commonOperation.post({
  title: 'Завершить анкету',
  tag: SurveyTag,
  isImplemented: true,
  operationId: 'applySurvey',
  requestSchema: ApplySurveyRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem, EntitiesNotFoundByIdsApiProblem],
});

commonOperation.get({
  title: 'Получить список анкет',
  tag: SurveyTag,
  isImplemented: true,
  operationId: 'listSurveys',
  parameters: ListSurveysParameters,
  responseSchema: ListSurveyResponseSchema,
});
