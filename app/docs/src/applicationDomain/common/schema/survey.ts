import { ref } from '../../../utils/ref';
import { arraySchema, booleanSchema, objectSchema, stringSchema } from '../../../utils/schemaFactory';
import { Uuid } from '../../../schema/common';
import { EmployeeId, EmployeeName } from './employee';
import { collectionWithItemsAmount } from '../../../schema/collection';
import { enumeration } from '../../../utils/enum';
import { PaginationParameters } from '../../../schema/pagination';

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

export const SurveyIsCompleted = ref.schema(
  'SurveyIsCompleted',
  booleanSchema({
    description: 'Завершена ли анкета',
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

export const QuestionsId = { ...Uuid, description: 'ID вопроса' };

const QuestionAnswer = ref.schema(
  'QuestionAnswer',
  stringSchema({
    description: 'Текст ответа',
    examples: ['Какой-то ответ'],
    minLength: 1,
    maxLength: 255,
  }),
);

const ApplySurveyAnswerData = ref.schema(
  'ApplySurveyAnswerData',
  objectSchema({
    description: 'ID вопроса с текстом ответа',
    properties: {
      questionId: QuestionsId,
      answer: QuestionAnswer,
    },
  }),
);

const ApplySurveyAnswers = ref.schema(
  'ApplySurveyAnswers',
  arraySchema({
    description: 'Массив ответов на вопросы',
    items: ApplySurveyAnswerData,
    uniqueItems: true,
    minItems: 1,
    maxItems: 30,
  }),
);

export const ApplySurveyRequestSchema = ref.schema(
  'ApplySurveyRequestSchema',
  objectSchema({
    description: 'Данные для завершения анкеты',
    properties: {
      surveyId: SurveyId,
      answers: ApplySurveyAnswers,
    },
  }),
);

const SurveyEmployee = ref.schema(
  'SurveyEmployee',
  objectSchema({
    description: 'Данные сотрудника анкеты',
    properties: {
      employeeId: EmployeeId,
      name: EmployeeName,
    },
  }),
);

const ListSurveyResponseItem = ref.schema(
  'ListSurveyResponseItem',
  objectSchema({
    description: 'Данные анкеты',
    properties: {
      surveyId: SurveyId,
      isCompleted: SurveyIsCompleted,
      employee: SurveyEmployee,
    },
  }),
);

const SurveyStatusEnum = ref.schema(
  'SurveyStatusEnum',
  enumeration({
    description: 'Статусы анкеты',
    enumsWithDescriptions: {
      all: 'Все анкеты',
      completed: 'Завершённые анкеты',
      incomplete: 'Незавершённые анкеты',
    },
  }),
);

const ListSurveysStatusParameter = ref.parameter('ListSurveysStatusParameter', {
  name: 'status',
  in: 'query',
  required: true,
  schema: SurveyStatusEnum,
});

export const ListSurveysParameters = [...PaginationParameters, ListSurveysStatusParameter];

export const ListSurveyResponseSchema = collectionWithItemsAmount('ListSurveyResponseSchema', ListSurveyResponseItem);
