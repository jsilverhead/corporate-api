import type { SchemaObject } from '@fosfad/openapi-typescript-definitions/3.1.0';

export const enumeration = <T extends { [p: string]: string }>(params: {
  default?: keyof T;
  description: string;
  enumsWithDescriptions: T;
}): SchemaObject => {
  const enumValues = Object.keys(params.enumsWithDescriptions).sort((a: string, b: string) => a.localeCompare(b));

  params.description += '\n\n';
  params.description += '**Все возможные значения:**\n\n';

  for (const enumName of enumValues) {
    params.description += `- \`${enumName}\`: ${params.enumsWithDescriptions[enumName]}\n`;
  }

  return {
    description: params.description,
    default: params.default,
    type: 'string',
    enum: enumValues,
    examples: Object.keys(params.enumsWithDescriptions),
  };
};
