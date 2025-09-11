import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const NewsIsAlreadyPublishedApiProblem = ref.schema(
  'NewsIsAlreadyPublishedApiProblem',
  apiProblem({
    description: 'Новость уже опубликована',
    type: 'news_is_already_published',
  }),
);
