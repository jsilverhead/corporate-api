import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const EntityNotFoundApiProblem = ref.schema(
  'EntityNotFoundApiProblem',
  apiProblem({
    description: 'Сущность не найдена',
    type: 'entity_not_found',
  }),
);
