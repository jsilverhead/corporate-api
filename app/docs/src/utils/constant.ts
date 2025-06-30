import type { SchemaObject } from '@fosfad/openapi-typescript-definitions/3.1.0';

export const constant = (params: { description: string; value: unknown }): SchemaObject => {
  return {
    description: params.description,
    enum: [params.value],
  };
};
