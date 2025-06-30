import { ref } from '../utils/ref';
import type { SecurityRequirement } from '@fosfad/openapi-typescript-definitions/3.1.0';

export const OAUTH_SECURITY_NAME = 'AppUser';
export const BEARER_SECURITY_SCHEMA = 'Bearer';

ref.securityScheme(OAUTH_SECURITY_NAME, {
  description: 'См. раздел "OAuth 2.0".',
  flows: {
    authorizationCode: {
      authorizationUrl: '/requestAuthenticationSmsCode',
      tokenUrl: '/issueTokenBySmsCode',
      scopes: {},
    },
  },
  type: 'oauth2',
});

ref.securityScheme(BEARER_SECURITY_SCHEMA, {
  description: `Авторизация по JWT Bearer-токену.\n Ожидается JWT access-token в \`headers\` в поле \`Authorization\` в формате \`Bearer {token}\``,
  type: 'apiKey',
  in: 'header',
  name: 'Authorization',
});

export const AuthorizationSecuritySchema: SecurityRequirement[] = [
  {
    [OAUTH_SECURITY_NAME]: [],
    [BEARER_SECURITY_SCHEMA]: [],
  },
];
