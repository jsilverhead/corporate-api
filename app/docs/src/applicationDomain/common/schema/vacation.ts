import { ref } from '../../../utils/ref';
import { arraySchema, booleanSchema, objectSchema } from '../../../utils/schemaFactory';
import { DateTime, PeriodFromDateParameter, PeriodToDateParameter, Uuid } from '../../../schema/common';
import { collectionWithItemsAmount } from '../../../schema/collection';
import { DepartmentId, DepartmentName } from './department';
import { EmployeeId, EmployeeName } from './employee';
import { PaginationParameters } from '../../../schema/pagination';
import { enumeration } from '../../../utils/enum';

export const VacationId = { ...Uuid, description: 'ID отпуска' };

const Period = ref.schema(
  'Period',
  objectSchema({
    description: 'Период',
    properties: {
      fromDate: DateTime,
      toDate: DateTime,
    },
  }),
);

export const CreateVacationRequestSchema = ref.schema(
  'CreateVacationRequestSchema',
  objectSchema({
    description: 'Данные для создания отпуска',
    properties: {
      period: Period,
    },
  }),
);

export const CreateVacationResponseSchema = ref.schema(
  'CreateVacationResponseSchema',
  objectSchema({
    description: 'Данные созданного отпуска',
    properties: {
      id: VacationId,
    },
  }),
);

export const VacationIsApproved = ref.schema(
  'VacationIsApproved',
  booleanSchema({
    description: 'Одобрен ли отпуск',
  }),
);

const ListVacationsVacationInfo = ref.schema(
  'ListVacationsVacationInfo',
  objectSchema({
    description: 'Данные отпуска',
    properties: {
      vacationId: VacationId,
      fromDate: DateTime,
      toDate: DateTime,
      isApproved: VacationIsApproved,
    },
  }),
);

const ListVacationsEmployees = ref.schema(
  'ListVacationsEmployees',
  objectSchema({
    description: 'Данные сотрудника',
    properties: {
      employeeId: EmployeeId,
      employeeName: EmployeeName,
      vacations: arraySchema({
        description: 'Массив отпусков',
        items: ListVacationsVacationInfo,
        minItems: 0,
        maxItems: undefined,
        uniqueItems: true,
      }),
    },
  }),
);

const ListVacationsItem = ref.schema(
  'ListVacationsItem',
  objectSchema({
    description: 'Данные отдела',
    properties: {
      departmentId: DepartmentId,
      departmentName: DepartmentName,
      employees: arraySchema({
        description: 'Массив сотрудников',
        items: ListVacationsEmployees,
        minItems: 0,
        maxItems: undefined,
        uniqueItems: true,
      }),
    },
  }),
);

export const ListVacationsResponseSchema = collectionWithItemsAmount('ListVacationsResponseSchema', ListVacationsItem);

const ListVacationsEmployeeIdParameter = ref.parameter('ListVacationsEmployeeIdParameter', {
  in: 'query',
  name: 'employeeId',
  schema: EmployeeId,
  required: false,
});

const ListVacationsDepartmentIdParameter = ref.parameter('ListVacationsDepartmentIdParameter', {
  in: 'query',
  name: 'departmentId',
  schema: DepartmentId,
  required: false,
});

const ListVacationsStatusEnum = ref.schema(
  'ListVacationsStatusEnum',
  enumeration({
    description: 'Статусы отпуска',
    enumsWithDescriptions: {
      all: 'Все',
      approved: 'Одобренные',
      unapproved: 'Не одобренные',
    },
  }),
);

const ListVacationsStatusParam = ref.parameter('ListVacationsStatusParam', {
  in: 'query',
  name: 'status',
  schema: ListVacationsStatusEnum,
  required: true,
});

export const ListVacationsQueryParameters = [
  ...PaginationParameters,
  ListVacationsEmployeeIdParameter,
  ListVacationsDepartmentIdParameter,
  PeriodFromDateParameter,
  PeriodToDateParameter,
  ListVacationsStatusParam,
];

export const ApproveVacationRequestSchema = ref.schema(
  'ApproveVacationRequestSchema',
  objectSchema({
    description: 'Данные для одобрения отпуска',
    properties: {
      id: VacationId,
    },
  }),
);

export const UpdateVacationRequestSchema = ref.schema(
  'UpdateVacationRequestSchema',
  objectSchema({
    description: 'Данные для обновления отпуска',
    properties: {
      id: VacationId,
      period: Period,
    },
  }),
);
