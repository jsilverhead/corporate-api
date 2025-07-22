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
