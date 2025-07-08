import { ref } from '../../../utils/ref';
import { stringSchema } from '../../../utils/schemaFactory';
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
