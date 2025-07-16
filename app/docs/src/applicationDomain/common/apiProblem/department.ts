import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const DepartmentWithThisNameAlreadyExistsApiProblem = ref.schema(
  'DepartmentWithThisNameAlreadyExistsApiProblem',
  apiProblem({
    description: 'Отдел с таким именем уже существует',
    type: 'department_with_this_name_already_exists',
  }),
);

export const EmployeeAlreadySupervisingThisDepartmentApiProblem = ref.schema(
  'EmployeeAlreadySupervisingThisDepartmentApiProblem',
  apiProblem({
    description: 'Сотрудник уже курирует данный отдел',
    type: 'employee_already_supervising_this_department',
  }),
);

export const EmployeeDoNotSupervisingThisDepartmentApiProblem = ref.schema(
  'EmployeeDoNotSupervisingThisDepartmentApiProblem',
  apiProblem({
    description: 'Сотрудник не курирует данный отдел',
    type: 'employee_do_not_supervise_this_department',
  }),
);

export const EmployeeAlreadyInTheDepartmentApiProblem = ref.schema(
  'EmployeeAlreadyInTheDepartmentApiProblem',
  apiProblem({
    description: 'Сотрудник уже принадлежит данному отделу',
    type: 'employee_already_in_the_department',
  }),
);

export const EmployeeIsNotInTheDepartmentApiProblem = ref.schema(
  'EmployeeIsNotInTheDepartmentApiProblem',
  apiProblem({
    description: 'Сотрудник не принадлежит данному отделу',
    type: 'employee_is_not_in_the_department',
  }),
);

export const DepartmentAlreadyDeletedApiProblem = ref.schema(
  'DepartmentAlreadyDeletedApiProblem',
  apiProblem({
    description: 'Отдел уже удалён',
    type: 'department_already_deleted',
  }),
);
