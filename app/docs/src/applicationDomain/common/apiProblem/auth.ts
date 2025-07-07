import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const PasswordIsInvalidApiProblem = ref.schema(
  'PasswordIsInvalidApiProblem',
  apiProblem({
    description: 'Указан неверный пароль',
    type: 'password_is_invalid',
  }),
);

export const UnknownTokenApiProblem = ref.schema(
  'UnknownTokenApiProblem',
  apiProblem({
    description: 'Токен не найден',
    type: 'unknown_token',
  }),
);

export const ExpiredJwtTokenApiProblem = ref.schema(
  'ExpiredJwtTokenApiProblem',
  apiProblem({
    description: 'Время жизни токена прошло',
    type: 'expired_access_token',
  }),
);

export const JwtTokenIsInvalidApiProblem = ref.schema(
  'JwtTokenIsInvalidApiProblem',
  apiProblem({
    description: 'Неверный токен',
    type: 'jwt_token_is_invalid',
  }),
);
