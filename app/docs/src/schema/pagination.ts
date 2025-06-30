import { integerSchema } from '../utils/schemaFactory';
import { ref } from '../utils/ref';

export const PaginationMaxItemsAmount = 50;

export const PaginationHowManyElementsToReturn = ref.schema(
  'PaginationHowManyElementsToReturn',
  integerSchema({
    minimum: 1,
    maximum: PaginationMaxItemsAmount,
    description: 'Пагинация: сколько ресурсов необходимо получить.',
    examples: [10],
  }),
);

export const PaginationHowManyElementsToOffset = ref.schema(
  'PaginationHowManyElementsToOffset',
  integerSchema({
    minimum: 0,
    maximum: undefined,
    description: 'Пагинация: сколько ресурсов необходимо пропустить.',
    examples: [0],
  }),
);

export const PaginationItemsAmount = ref.schema(
  'PaginationItemsAmount',
  integerSchema({
    minimum: 0,
    maximum: undefined,
    description: 'Сколько всего элементов в системе.',
    examples: [100500],
  }),
);

const PaginationOffsetParameter = ref.parameter('PaginationOffset', {
  name: 'pagination[offset]',
  in: 'query',
  schema: PaginationHowManyElementsToOffset,
  required: true,
});

const PaginationCountParameter = ref.parameter('PaginationCount', {
  name: 'pagination[count]',
  in: 'query',
  schema: PaginationHowManyElementsToReturn,
  required: true,
});

export const PaginationParameters = [PaginationOffsetParameter, PaginationCountParameter];
