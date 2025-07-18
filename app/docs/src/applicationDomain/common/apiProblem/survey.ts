import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const EmployeeAlreadyHasASurveyApiProblem = ref.schema(
  'EmployeeAlreadyHasASurveyApiProblem',
  apiProblem({
    description: 'У сотрудника уже есть существующая анкета',
    type: 'employee_already_has_survey',
  }),
);
