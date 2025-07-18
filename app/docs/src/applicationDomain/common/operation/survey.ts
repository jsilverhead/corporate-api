import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import { CreateSurveyTemplateRequestSchema, CreateSurveyTemplateResponseSchema } from '../schema/survey';

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
