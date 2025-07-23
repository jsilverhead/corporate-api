import { ref } from '../../../utils/ref';
import { objectSchema } from '../../../utils/schemaFactory';
import { DateTime, Uuid } from '../../../schema/common';

export const VacationId = { ...Uuid, description: 'ID отпуска' };

const Period = ref.schema(
  'Period',
  objectSchema({
    description: 'Период',
    properties: {
      fromDate: DateTime,
      toDate: DateTime,
    },
  }),
);

export const CreateVacationRequestSchema = ref.schema(
  'CreateVacationRequestSchema',
  objectSchema({
    description: 'Данные для создания отпуска',
    properties: {
      period: Period,
    },
  }),
);

export const CreateVacationResponseSchema = ref.schema(
  'CreateVacationResponseSchema',
  objectSchema({
    description: 'Данные созданного отпуска',
    properties: {
      id: VacationId,
    },
  }),
);
