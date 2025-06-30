import { ref } from '../../../utils/ref';
import { enumeration } from '../../../utils/enum';
import { arraySchema, integerSchema, objectSchema, stringSchema } from '../../../utils/schemaFactory';
import { DateTime, Url } from '../../../schema/common';
import { nullable } from '../../../utils/nullable';

export const FilePurpose = ref.schema(
  'FilePurpose',
  enumeration({
    description: 'Назначение файла.',
    enumsWithDescriptions: {
      avatar: 'Аватар (изображение профиля).',
    },
  }),
);

export const FileMimeType = ref.schema(
  'FileMimeType',
  enumeration({
    description: 'MIME type файла.',
    enumsWithDescriptions: {
      'image/jpeg': 'JPEG изображение.',
      'image/png': 'PNG изображение.',
      'application/pdf': 'PDF документ.',
    },
  }),
);

export const FileSize = ref.schema(
  'FileSize',
  integerSchema({
    examples: [1024],
    description: 'Размер файла, в байтах.',
    minimum: undefined,
    maximum: undefined,
  }),
);

export const ImageWidth = ref.schema(
  'ImageWidth',
  integerSchema({
    examples: [1920],
    description: 'Ширина картинки, в пикселях.',
    minimum: 0,
    maximum: undefined,
  }),
);

export const ImageHeight = ref.schema(
  'ImageHeight',
  integerSchema({
    examples: [1920],
    description: 'Высота картинки, в пикселях.',
    minimum: 0,
    maximum: undefined,
  }),
);

export const FileName = ref.schema(
  'FileName',
  stringSchema({
    examples: ['file.pdf'],
    minLength: 1,
    maxLength: undefined,
    description: 'Имя файла.',
  }),
);

export const UploadedFileToken = ref.schema(
  'UploadedFileToken',
  stringSchema({
    minLength: undefined,
    maxLength: undefined,
    description: 'JWT с информацией о загруженном файле.',
    pattern: '^[\\w-]+\\.[\\w-]+\\.[\\w-]+$',
    examples: [
      'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c',
    ],
  }),
);

export const MinimumImageResolution = ref.schema(
  'MinimumImageResolution',
  objectSchema({
    description: 'Минимальные ширина и высота картинки.',
    properties: {
      width: ImageWidth,
      height: ImageHeight,
    },
  }),
);

export const MaximumImageResolution = ref.schema(
  'MaximumImageResolution',
  objectSchema({
    description: 'Максимальные ширина и высота картинки.',
    properties: {
      width: ImageWidth,
      height: ImageHeight,
    },
  }),
);

export const RangeImageResolutions = ref.schema(
  'RangeImageResolutions',
  objectSchema({
    description: 'Разрешение картинки.',
    properties: {
      min: MinimumImageResolution,
      max: MaximumImageResolution,
    },
  }),
);

const MaxFileSize = ref.schema(
  'MaxFileSize',
  objectSchema({
    description: 'Максимальный допустимый размер файла в байтах.',
    properties: {
      max: FileSize,
    },
  }),
);

const FileConstraintsItem = ref.schema(
  'FileConstraintsItem',
  objectSchema({
    description: 'Ограничения накладываемые на соответствующий MIME-тип.',
    properties: {
      fileMimeType: FileMimeType,
      fileSize: MaxFileSize,
      resolution: nullable(RangeImageResolutions),
    },
  }),
);

export const FileConstraints = ref.schema(
  'FileConstraints',
  arraySchema({
    description: 'Ограничение для цели файла.',
    minItems: 1,
    uniqueItems: true,
    items: FileConstraintsItem,
  }),
);

export const FileUploadUrlRequestSchema = ref.schema(
  'FileUploadUrlRequestSchema',
  objectSchema({
    description: 'Данные для генерации ссылки.',
    properties: {
      purpose: FilePurpose,
    },
  }),
);

export const FileUploadUrlResponseSchema = ref.schema(
  'FileUploadUrlResponseSchema',
  objectSchema({
    description: 'Данные для загрузки файла.',
    additionalProperties: false,
    properties: {
      constraints: FileConstraints,
      url: Url,
      validTill: DateTime,
    },
  }),
);
