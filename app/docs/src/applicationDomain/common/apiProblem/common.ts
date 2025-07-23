import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const EntityNotFoundApiProblem = ref.schema(
  'EntityNotFoundApiProblem',
  apiProblem({
    description: 'Сущность не найдена',
    type: 'entity_not_found',
  }),
);

export const EntitiesNotFoundByIdsApiProblem = ref.schema(
  'EntitiesNotFoundByIdsApiProblem',
  apiProblem({
    description: 'Сущности не найдены',
    type: 'entities_not_found_by_ids',
  }),
);

export const ToDateCannotBeLessThanFromDateApiProblem = ref.schema(
  'ToDateCannotBeLessThanFromDateApiProblem',
  apiProblem({
    description: 'Дата окончания не может быть меньше даты старта',
    type: 'from_date_cannot_be_less_than_to_date',
  }),
);
