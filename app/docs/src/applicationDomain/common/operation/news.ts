import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { commonOperation } from '../path';
import {
  AddNewsRequestSchema,
  AddNewsResponseSchema,
  GetNewsIdParameter,
  GetNewsResponseSchema,
  ListNewsDateOrderDirectionEnum,
  ListNewsResponseSchema,
  PublishNewsRequestSchema,
  UpdateNewsRequestSchema,
} from '../schema/new';
import { EntityNotFoundApiProblem } from '../apiProblem/common';
import { NewsIsAlreadyPublishedApiProblem } from '../apiProblem/news';
import { PaginationParameters } from '../../../schema/pagination';

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

commonOperation.post({
  title: 'Опубликовать новость',
  tag: NewsTag,
  operationId: 'publishNews',
  isImplemented: true,
  requestSchema: PublishNewsRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem, NewsIsAlreadyPublishedApiProblem],
});

commonOperation.post({
  title: 'Обновить новость',
  tag: NewsTag,
  operationId: 'updateNews',
  isImplemented: true,
  requestSchema: UpdateNewsRequestSchema,
  errorSchemas: [EntityNotFoundApiProblem],
});

commonOperation.get({
  title: 'Получить новость',
  tag: NewsTag,
  operationId: 'getNews',
  isImplemented: true,
  parameters: [GetNewsIdParameter],
  responseSchema: GetNewsResponseSchema,
  errorSchemas: [EntityNotFoundApiProblem],
});

commonOperation.get({
  title: 'Получить список новостей',
  tag: NewsTag,
  operationId: 'listNews',
  isImplemented: true,
  parameters: [...PaginationParameters, ListNewsDateOrderDirectionEnum],
  responseSchema: ListNewsResponseSchema,
});
