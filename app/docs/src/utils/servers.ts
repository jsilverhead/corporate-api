import type { Server } from '@fosfad/openapi-typescript-definitions/3.1.0';

export const NonProductionServer: Server = {
  description: 'Сервер с не-продакшн окружением.',
  url: 'https://api.{environment}.yma.spiks.dev',
  variables: {
    environment: {
      enum: ['dev', 'stage'],
      default: 'dev',
      description: 'Название окружения.',
    },
  },
};

export const ProductionServer: Server = {
  description: 'Продакшн-сервер.',
  url: 'https://TEMPLATE.ru',
};

export const LocalServer: Server = {
  description: 'Сервер, запущенный локально.',
  url: 'http://localhost:{port}',
  variables: {
    port: {
      default: '8080',
      description: 'Порт, на котором запущен сервер.',
    },
  },
};
