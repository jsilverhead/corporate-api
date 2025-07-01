import { apiProblem } from '../../../schema/api-problem';
import { ref } from '../../../utils/ref';

export const UserWithThisEmailAlreadyExistsApiProblem = ref.schema(
  'UserWithThisEmailAlreadyExistsApiProblem',
  apiProblem({
    description: 'Пользователь с данным Email уже существует в системе',
    type: 'user_with_this_email_already_exists',
  }),
);

export const UserAlreadyDeletedApiProblem = ref.schema(
  'UserAlreadyDeletedApiProblem',
  apiProblem({
    description: 'Пользователь уже удалён',
    type: 'user_already_deleted',
  }),
);
