import { DateTime, Uuid } from '../../../schema/common';
import { ref } from '../../../utils/ref';
import { booleanSchema, objectSchema, stringSchema } from '../../../utils/schemaFactory';
import { EmployeeId, EmployeeName } from './employee';
import { enumeration } from '../../../utils/enum';
import { collectionWithItemsAmount } from '../../../schema/collection';

export const NewsId = { ...Uuid, description: 'ID новости' };

export const NewsCreatedAt = { ...DateTime, description: 'Дата создания новости' };

export const NewsTitle = ref.schema(
  'NewsTitle',
  stringSchema({
    description: 'Заголовок новости',
    minLength: 5,
    maxLength: 255,
    examples: ['Выходн нового сотрудника'],
  }),
);

export const NewsContent = ref.schema(
  'NewsContent',
  stringSchema({
    description: 'Текст новости',
    minLength: 5,
    maxLength: 10000,
    examples: ['Сердечно поздравляем от всего коллектива'],
  }),
);

export const NewsPreview = ref.schema(
  'NewsPreview',
  stringSchema({
    description: 'Короткий текст предпросмотра',
    minLength: 5,
    maxLength: 100,
    examples: ['Сердечно поздравляем от всего коллектива'],
  }),
);

export const NewsIsPublished = ref.schema(
  'NewsIsPublished',
  booleanSchema({
    description: 'Опубликована ли новость',
  }),
);

export const AddNewsRequestSchema = ref.schema(
  'AddNewsRequestSchema',
  objectSchema({
    description: 'Данные для добавления новости',
    properties: {
      title: NewsTitle,
      content: NewsContent,
    },
  }),
);

export const AddNewsResponseSchema = ref.schema(
  'AddNewsResponseSchema',
  objectSchema({
    description: 'Данные добавленой новости',
    properties: {
      id: NewsId,
    },
  }),
);

export const PublishNewsRequestSchema = ref.schema(
  'PublishNewsRequestSchema',
  objectSchema({
    description: 'Данные для публикации новости',
    properties: {
      id: NewsId,
    },
  }),
);

export const UpdateNewsRequestSchema = ref.schema(
  'UpdateNewsRequestSchema',
  objectSchema({
    description: 'Данные для обновления новости',
    properties: {
      id: NewsId,
      title: NewsTitle,
      content: NewsContent,
    },
  }),
);

export const GetNewsIdParameter = ref.parameter('GetNewsIdParameter', {
  description: 'ID запрашиваемой новости',
  name: 'id',
  in: 'query',
  required: true,
  schema: NewsId,
});

const NewsAuthorInfo = ref.schema(
  'GetNewsAuthorInfo',
  objectSchema({
    description: 'Данные автора новости',
    properties: {
      id: EmployeeId,
      name: EmployeeName,
    },
  }),
);

export const GetNewsResponseSchema = ref.schema(
  'GetNewsResponseSchema',
  objectSchema({
    description: 'Данные новости',
    properties: {
      id: NewsId,
      title: NewsTitle,
      content: NewsContent,
      author: NewsAuthorInfo,
      isPublished: NewsIsPublished,
    },
  }),
);

const NewsOrderDirectionEnum = ref.schema(
  'NewsOrderDirectionEnum',
  enumeration({
    description: 'Направление сортировки',
    enumsWithDescriptions: {
      asc: 'В порядке возрастания',
      desc: 'в порядке убывания',
    },
  }),
);

export const ListNewsDateOrderDirectionEnum = ref.parameter('ListNewsDateOrderDirectionEnum', {
  description: 'Сортировка новостей по дате',
  schema: NewsOrderDirectionEnum,
  in: 'query',
  name: 'orderByDate',
  required: true,
});

const ListNewsItemsSchema = ref.schema(
  'ListNewsItemsSchema',
  objectSchema({
    description: 'Данные новости',
    properties: {
      id: NewsId,
      title: NewsTitle,
      preview: NewsPreview,
      createdAt: NewsCreatedAt,
      isPublished: NewsIsPublished,
      author: NewsAuthorInfo,
    },
  }),
);

export const ListNewsResponseSchema = collectionWithItemsAmount('ListNewsResponseSchema', ListNewsItemsSchema);
