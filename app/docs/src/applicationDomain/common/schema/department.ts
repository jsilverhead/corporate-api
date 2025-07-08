import { ref } from '../../../utils/ref';
import { objectSchema, stringSchema } from '../../../utils/schemaFactory';
import { Uuid } from '../../../schema/common';

export const DepartmentName = ref.schema(
  'DepartmentName',
  stringSchema({
    description: 'Имя отдела',
    minLength: 1,
    maxLength: 255,
    examples: ['Департамент HR'],
  }),
);

export const DepartmentId = { ...Uuid, description: 'ID отдела' };

export const CreateDepartmentRequestSchema = ref.schema(
  'CreateDepartmentRequestSchema',
  objectSchema({
    description: 'Данные для создания отдела',
    properties: {
      name: DepartmentName,
    },
  }),
);

export const CreateDepartmentResponseSchema = ref.schema(
  'CreateDepartmentResponseSchema',
  objectSchema({
    description: 'Данные созданного отдела',
    properties: {
      id: DepartmentId,
    },
  }),
);
