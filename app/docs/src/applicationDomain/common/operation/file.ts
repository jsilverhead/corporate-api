import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import { FileUploadUrlRequestSchema, FileUploadUrlResponseSchema } from '../schema/file';

export const FileTag: Tag = {
  name: 'File',
  description: 'Файлы.',
};

commonOperation.post({
  isImplemented: false,
  title: 'Сгенерировать ссылку для загрузки файла',
  operationId: 'requestFileUploadUrl',
  tag: FileTag,
  requestSchema: FileUploadUrlRequestSchema,
  responseSchema: FileUploadUrlResponseSchema,
});
