import { ref } from '../../../utils/ref';
import { arraySchema, objectSchema, stringSchema } from '../../../utils/schemaFactory';
import { Uuid } from '../../../schema/common';
import { EmployeeId } from './employee';
import { collectionWithItemsAmount } from '../../../schema/collection';

export const SurveyTemplateId = { ...Uuid, description: 'ID шаблона анкеты' };

export const SurveyId = { ...Uuid, description: 'ID анкеты' };

export const SurveyTemplateName = ref.schema(
  'SurveyTemplateName',
  stringSchema({
    description: 'Название шаблона анкеты',
    examples: ['Новая 1'],
    minLength: 1,
    maxLength: 255,
  }),
);

const CreateSurveyTemplateQuestion = ref.schema(
  'CreateSurveyTemplateQuestion',
  stringSchema({
    description: 'Вопрос в виде строки',
    examples: ['Есть ли у вас какие-то достижения на предыдущем местер работы?'],
    minLength: 1,
    maxLength: 255,
  }),
);

const CreateSurveyTemplateQuestionsArray = ref.schema(
  'CreateSurveyTemplateQuestionsArray',
  arraySchema({
    description: 'Массив вопросов',
    items: CreateSurveyTemplateQuestion,
    minItems: 1,
    uniqueItems: false,
  }),
);

export const CreateSurveyTemplateRequestSchema = ref.schema(
  'CreateSurveyTemplateRequestSchema',
  objectSchema({
    description: 'Вопросы для создания шаблона',
    properties: {
      name: SurveyTemplateName,
      questions: CreateSurveyTemplateQuestionsArray,
    },
  }),
);

export const CreateSurveyTemplateResponseSchema = ref.schema(
  'CreateSurveyTemplateResponseSchema',
  objectSchema({
    description: 'Данные созданного шаблона анкеты',
    properties: {
      id: SurveyTemplateId,
    },
  }),
);

export const CreateSurveyRequestSchema = ref.schema(
  'CreateSurveyRequestSchema',
  objectSchema({
    description: 'Данные для создания анкеты',
    properties: {
      employeeId: EmployeeId,
      templateId: SurveyTemplateId,
    },
  }),
);

export const CreateSurveyResponseSchema = ref.schema(
  'CreateSurveyResponseSchema',
  objectSchema({
    description: 'Данные созданной анкеты',
    properties: {
      id: SurveyId,
    },
  }),
);
const ListSurveyTemplatesResponseItemSchema = ref.schema(
  'ListSurveyTemplatesResponseItemSchema',
  objectSchema({
    description: 'Данные о шаблоне',
    properties: {
      id: SurveyTemplateId,
      name: SurveyTemplateName,
    },
  }),
);

export const ListSurveyTemplatesResponseSchema = collectionWithItemsAmount(
  'ListSurveyTemplatesResponseSchema',
  ListSurveyTemplatesResponseItemSchema,
);

export const DeleteSurveyTemplateRequestSchema = ref.schema(
  'DeleteSurveyTemplateRequestSchema',
  objectSchema({
    description: 'Данные для удаления шаблона анкеты',
    properties: {
      id: SurveyTemplateId,
    },
  }),
);
