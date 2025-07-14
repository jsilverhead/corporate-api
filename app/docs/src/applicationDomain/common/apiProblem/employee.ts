import { apiProblem } from '../../../schema/api-problem';
import { ref } from '../../../utils/ref';

export const EmployeeWithThisEmailAlreadyExistsApiProblem = ref.schema(
  'EmployeeWithThisEmailAlreadyExistsApiProblem',
  apiProblem({
    description: 'Сотрудник с данным Email уже существует в системе',
    type: 'employee_with_this_email_already_exists',
  }),
);

export const EmployeeAlreadyDeletedApiProblem = ref.schema(
  'EmployeeAlreadyDeletedApiProblem',
  apiProblem({
    description: 'Сотрудник уже удалён',
    type: 'user_already_deleted',
  }),
);
