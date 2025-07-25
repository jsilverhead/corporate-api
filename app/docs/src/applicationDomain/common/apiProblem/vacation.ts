import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const CanNotUpdateApprovedVacationApiProblem = ref.schema(
  'CanNotUpdateApprovedVacationApiProblem',
  apiProblem({
    description: 'Невозможно изменить одобренный отпуск',
    type: 'can_not_update_approved_vacation',
  }),
);

export const FromDateCanNotBeInThePastApiProblem = ref.schema(
  'FromDateCanNotBeInThePastApiProblem',
  apiProblem({
    description: 'Дата старта отпуска не может быть в прошлом',
    type: 'from_date_cannot_be_in_the_past',
  }),
);

export const AnotherVacationExistsInsideTheChosenPeriodApiProblem = ref.schema(
  'AnotherVacationExistsInsideTheChosenPeriodApiProblem',
  apiProblem({
    description: 'Отпуск пересекается датой с другим отпуском сотрудника',
    type: 'another_vacation_exists_in_the_chosen_period',
  }),
);
