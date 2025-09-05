import { ref } from '../../../utils/ref';
import { apiProblem } from '../../../schema/api-problem';

export const VacationCanNotBeInThePastApiProblem = ref.schema(
  'VacationCanNotBeInThePastApiProblem',
  apiProblem({
    description: 'Дата отпуска не может быть в прошлом',
    type: 'vacation_can_not_be_in_the_past',
  }),
);

export const FromDateCanNotBeLessThatFourteenDaysFromNowApiProblem = ref.schema(
  'FromDateCanNotBeLessThatFourteenDaysFromNowApiProblem',
  apiProblem({
    description: 'Дата старта отпуска должна быть больше или равна 14 дням от текущего времени',
    type: 'from_date_cannot_be_less_that_fourteen_days_from_now',
  }),
);

export const CanNotUpdateApprovedVacationApiProblem = ref.schema(
  'CanNotUpdateApprovedVacationApiProblem',
  apiProblem({
    description: 'Невозможно обновить одобренный отпуск',
    type: 'can_not_update_approved_vacation',
  }),
);
