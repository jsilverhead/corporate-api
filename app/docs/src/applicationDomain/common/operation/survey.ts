import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import {
  CreateSurveyRequestSchema,
  CreateSurveyResponseSchema,
  CreateSurveyTemplateRequestSchema,
  CreateSurveyTemplateResponseSchema,
  ListSurveyTemplatesResponseSchema,
} from '../schema/survey';
import { EntityNotFoundApiProblem } from '../apiProblem/common';
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
