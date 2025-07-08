import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const DepartmentWithThisNameAlreadyExistsApiProblem = ref.schema(
  'DepartmentWithThisNameAlreadyExistsApiProblem',
  apiProblem({
    description: 'Отдел с таким именем уже существует',
    type: 'department_with_this_name_already_exists',
  }),
);
