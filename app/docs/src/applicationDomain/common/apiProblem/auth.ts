import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const PasswordIsInvalidApiProblem = ref.schema(
  'PasswordIsInvalidApiProblem',
  apiProblem({
    description: 'Указан неверный пароль',
    type: 'password_is_invalid',
  }),
);
