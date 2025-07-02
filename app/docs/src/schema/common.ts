import { ref } from '../utils/ref';
import { booleanSchema, floatSchema, integerSchema, stringSchema } from '../utils/schemaFactory';
import { enumeration } from '../utils/enum';
import { objectSchema } from '../utils/schemaFactory';

export const Uuid = ref.schema(
  'Uuid',
  stringSchema({
    format: 'uuid',
    description: 'Идентификатор в формате UUID.',
    minLength: 36,
    maxLength: 36,
    examples: ['d274b02e-646c-4624-b623-8a75e75d4293'],
  }),
);

export const DateTime = ref.schema(
  'DateTime',
  stringSchema({
    format: 'date-time',
    description: 'Дата и время.',
    examples: ['1996-04-17T22:55:33+00:00'],
  }),
);

export const Date = ref.schema(
  'Date',
  stringSchema({
    format: 'date',
    description: 'Дата',
    examples: ['2001-03-19'],
  }),
);

export const Url = ref.schema(
  'Url',
  stringSchema({
    description: 'Ссылка.',
    format: 'uri',
    examples: ['https://example.com'],
  }),
);

export const Seconds = ref.schema(
  'Seconds',
  integerSchema({
    examples: [120],
    description: 'Количество секунд.',
  }),
);

export const SecondsAsFloat = ref.schema(
  'SecondsAsFloat',
  floatSchema({
    examples: [15.15],
    description: 'Количество секунд в виде числа с плавающей точкой.',
  }),
);

export const FullName = ref.schema(
  'FullName',
  stringSchema({
    description: 'ФИО.',
    minLength: 1,
    examples: ['Степан Арвеладзе'],
  }),
);

export const ConfirmationCode = ref.schema(
  'ConfirmationCode',
  stringSchema({
    examples: ['1337'],
    pattern: '^\\d{4}$',
    description: 'Код подтверждения.',
  }),
);

export const BooleanString = ref.schema(
  'BooleanString',
  enumeration({
    description: 'Булево значение, представленное в виде строки.',
    enumsWithDescriptions: {
      '1': 'Значение для `true`.',
      '0': 'Значение для `false`.',
    },
  }),
);

export const IsArchived = ref.schema(
  'IsArchived',
  booleanSchema({
    description: 'Ресурс заархивирован или нет.',
  }),
);

export const PositionIndex = ref.schema(
  'PositionIndex',
  integerSchema({
    examples: [0, 1, 100],
    minimum: 0,
    description: 'Порядковый номер ресурса относительно других ресурсов.',
  }),
);

export const ResourcesAmount = ref.schema(
  'ResourcesAmount',
  objectSchema({
    description: 'Информация о количестве ресурсов.',
    properties: {
      amount: integerSchema({
        minimum: 0,
        description: 'Количество ресурсов.',
        examples: [5],
      }),
    },
  }),
);

export const Rate = ref.schema(
  'Rate',
  integerSchema({
    minimum: 0,
    maximum: 100,
    description: 'Ставка в %',
    examples: [50],
  }),
);

const Rubles = ref.schema(
  'Rubles',
  integerSchema({
    examples: [1000],
    description: 'Рубли.',
    minimum: 0,
    maximum: 21474835,
  }),
);

const Pennies = ref.schema(
  'Pennies',
  integerSchema({
    examples: [0],
    description: 'Копейки.',
    minimum: 0,
    maximum: 99,
  }),
);

export const Money = ref.schema(
  'Money',
  objectSchema({
    description: 'Деньги.',
    additionalProperties: false,
    properties: {
      pennies: Pennies,
      rubles: Rubles,
    },
  }),
);
