import type { Schema, SchemaObject } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { nullSchema } from './schemaFactory';

export const nullable = (schema: Schema): SchemaObject => {
  return {
    oneOf: [
      schema,
      nullSchema({
        description: 'Поле может быть `null`.',
      }),
    ],
  };
};

export const schemaOfNull = nullSchema({
  description: 'Поле может быть `null`.',
});
