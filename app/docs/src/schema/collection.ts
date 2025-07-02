import type { SchemaObject } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { ref } from '../utils/ref';
import { arraySchema, objectSchema } from '../utils/schemaFactory';
import { PaginationItemsAmount, PaginationMaxItemsAmount } from './pagination';

export const collectionWithItemsAmount = (key: string, schema: SchemaObject): SchemaObject => {
  return ref.schema(
    key,
    objectSchema({
      description: 'Коллекция, которая возвращает, сколько всего элементов в системе.',
      additionalProperties: false,
      properties: {
        items: arraySchema({
          minItems: 0,
          maxItems: PaginationMaxItemsAmount,
          uniqueItems: true,
          description: 'Коллекция.',
          items: schema,
        }),
        itemsAmount: PaginationItemsAmount,
      },
    }),
  );
};

export const collection = (
  key: string,
  schema: SchemaObject,
  options?: { maxItems?: number; minItems?: number },
): SchemaObject => {
  return ref.schema(
    key,
    objectSchema({
      description: 'Коллекция элементов.',
      properties: {
        items: arraySchema({
          description: 'Коллекция.',
          minItems: options?.minItems ?? 0,
          maxItems: options?.maxItems ?? undefined,
          uniqueItems: true,
          items: schema,
        }),
      },
    }),
  );
};
