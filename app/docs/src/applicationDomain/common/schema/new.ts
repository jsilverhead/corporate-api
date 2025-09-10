import { Uuid } from '../../../schema/common';
import { ref } from '../../../utils/ref';
import { objectSchema, stringSchema } from '../../../utils/schemaFactory';

export const NewsId = { ...Uuid, description: 'ID новости' };

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
    maxLength: 255,
    examples: ['Сердечно поздравляем от всего коллектива'],
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
