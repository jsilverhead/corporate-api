import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const DepartmentWithThisNameAlreadyExistsApiProblem = ref.schema(
  'DepartmentWithThisNameAlreadyExistsApiProblem',
  apiProblem({
    description: 'Отдел с таким именем уже существует',
    type: 'department_with_this_name_already_exists',
  }),
);

export const UserAlreadySupervisingThisDepartmentApiProblem = ref.schema(
  'UserAlreadySupervisingThisDepartmentApiProblem',
  apiProblem({
    description: 'Пользователь уже курирует данный отдел',
    type: 'user_already_supervising_this_department',
  }),
);

export const UserDoNotSupervisingThisDepartmentApiProblem = ref.schema(
  'UserDoNotSupervisingThisDepartmentApiProblem',
  apiProblem({
    description: 'Пользователь не курирует данный отдел',
    type: 'user_do_not_supervising_this_department',
  }),
);

export const UserAlreadyInTheDepartmentApiProblem = ref.schema(
  'UserAlreadyInTheDepartmentApiProblem',
  apiProblem({
    description: 'Пользователь не курирует данный отдел',
    type: 'user_do_not_supervising_this_department',
  }),
);
