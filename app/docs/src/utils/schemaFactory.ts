import type { SchemaObject } from '@fosfad/openapi-typescript-definitions/3.1.0';

export const objectSchema = (schema: {
  additionalProperties?: SchemaObject['additionalProperties'];
  deprecated?: SchemaObject['deprecated'];
  description: NonNullable<SchemaObject['description']>;
  externalDocs?: SchemaObject['externalDocs'];
  properties: NonNullable<SchemaObject['properties']>;
  title?: string;
}): SchemaObject => {
  return {
    ...schema,
    required: Object.keys(schema.properties).sort(),
    type: 'object',
  };
};

export const stringSchema = (schema: {
  description: NonNullable<SchemaObject['description']>;
  examples: NonNullable<SchemaObject['examples']>;
  externalDocs?: SchemaObject['externalDocs'];
  format?: SchemaObject['format'];
  maxLength?: SchemaObject['minLength'];
  minLength?: SchemaObject['minLength'];
  pattern?: SchemaObject['pattern'];
}): SchemaObject => {
  return {
    ...schema,
    type: 'string',
  };
};

export const arraySchema = (schema: {
  description: NonNullable<SchemaObject['description']>;
  items: NonNullable<SchemaObject['items']>;
  maxItems?: SchemaObject['maxItems'] | undefined;
  minItems: SchemaObject['minItems'];
  uniqueItems: NonNullable<SchemaObject['uniqueItems']>;
}): SchemaObject => {
  return {
    ...schema,
    type: 'array',
  };
};

export const integerSchema = (schema: {
  description: NonNullable<SchemaObject['description']>;
  examples: NonNullable<SchemaObject['examples']>;
  externalDocs?: SchemaObject['externalDocs'];
  maximum?: SchemaObject['maximum'];
  minimum?: SchemaObject['minimum'];
}): SchemaObject => {
  return {
    ...schema,
    type: 'integer',
  };
};

export const floatSchema = (schema: {
  description: NonNullable<SchemaObject['description']>;
  examples: NonNullable<SchemaObject['examples']>;
  externalDocs?: SchemaObject['externalDocs'];
  maximum?: SchemaObject['maximum'];
  minimum?: SchemaObject['minimum'];
}): SchemaObject => {
  return {
    ...schema,
    type: 'number',
  };
};

export const booleanSchema = (schema: { description: NonNullable<SchemaObject['description']> }): SchemaObject => {
  return {
    ...schema,
    type: 'boolean',
  };
};

export const nullSchema = (schema: SchemaObject = {}): SchemaObject => {
  return {
    ...schema,
    type: 'null',
  };
};
