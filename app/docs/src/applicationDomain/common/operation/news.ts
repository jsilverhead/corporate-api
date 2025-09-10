import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import { AddNewsRequestSchema, AddNewsResponseSchema } from '../schema/new';

export const NewsTag: Tag = {
  name: 'Новости',
};

commonOperation.post({
  title: 'Добавить новость',
  tag: NewsTag,
  operationId: 'addNews',
  isImplemented: true,
  requestSchema: AddNewsRequestSchema,
  responseSchema: AddNewsResponseSchema,
});
