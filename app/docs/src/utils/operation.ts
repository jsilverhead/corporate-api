import type {
  SchemaObject,
  Tag,
  Responses,
  Paths,
  Parameter,
  Reference,
  RequestBody,
} from '@fosfad/openapi-typescript-definitions/3.1.0';
import {
  AuthorizationHeaderMissingApiProblem,
  ExpiredAccessTokenApiProblem,
  InternalServerErrorApiProblem,
  InvalidAuthorizationHeaderApiProblem,
  ProfileDoesNotExistApiProblem,
  ServiceUnavailableApiProblem,
  TooManyRequestsApiProblem,
  UnknownAccessTokenApiProblem,
  UnparsableRequestApiProblem,
  ValidationErrorApiProblem,
} from '../schema/api-problem';
import { AuthorizationSecuritySchema } from '../schema/securitySchemes';
import type { SchemaReference } from './ref';
import { Operation, Response } from '@fosfad/openapi-typescript-definitions/3.1.0';

export type OperationEntry = {
  method: 'get' | 'post';
  operation: Operation;
};

export class ReferableOperations {
  private operations: { [operationId: string]: OperationEntry } = {};

  constructor(
    private readonly prefix: string,
    private readonly requiresAuthorization: boolean,
    private readonly defaultErrorSchemas: Array<SchemaObject> = [],
  ) {}

  public getPaths = (): Paths => {
    return Object.entries(this.operations)
      .map((entry) => entry[1])
      .sort((operationA, operationB) => {
        if (operationA.operation.operationId === undefined || operationB.operation.operationId === undefined) {
          return 0;
        }

        return operationA.operation.operationId.localeCompare(operationB.operation.operationId);
      })
      .reduce((accumulatedPaths: Paths, operation): Paths => {
        if (operation.operation.operationId === undefined) {
          throw new Error(`Operation is missing operationId: ${JSON.stringify(operation)}`);
        }

        accumulatedPaths[`${this.prefix}${operation.operation.operationId}`] = {
          [operation.method]: operation.operation,
        };

        return accumulatedPaths;
      }, {});
  };

  public get = (operation: {
    cacheControl?: true | undefined;
    deprecationMessage?: string | undefined;
    description?: string | undefined;
    errorSchemas?: Array<SchemaObject>;
    isImplemented: boolean;
    isRateLimited?: true | undefined;
    operationId: string;
    parameters?: Array<Parameter | Reference> | undefined;
    responseSchema?: SchemaObject | undefined;
    tag: Tag;
    title: string;
  }): void => {
    this.addApiMethod({ ...operation, method: 'get' });
  };

  public post = (operation: {
    cacheControl?: true | undefined;
    deprecationMessage?: string | undefined;
    description?: string | undefined;
    errorSchemas?: Array<SchemaObject>;
    isImplemented: boolean;
    isRateLimited?: true | undefined;
    operationId: string;
    requestSchema?: SchemaReference | undefined;
    responseSchema?: SchemaReference | undefined;
    tag: Tag;
    title: string;
  }): void => {
    this.addApiMethod({ ...operation, method: 'post' });
  };

  private addApiMethod = (operation: {
    cacheControl?: true | undefined;
    deprecationMessage?: string | undefined;
    description?: string | undefined;
    errorSchemas?: Array<SchemaObject>;
    isImplemented: boolean;
    isRateLimited?: true | undefined;
    method: 'get' | 'post';
    operationId: string;
    parameters?: Array<Parameter | Reference> | undefined;
    requestSchema?: SchemaObject | undefined;
    responseSchema?: SchemaObject | undefined;
    tag: Tag;
    title: string;
  }): void => {
    if (this.operations[operation.method] !== undefined) {
      throw new Error(`Duplicate operation ID: ${operation.method}`);
    }

    let requestBody: RequestBody | undefined;
    const errorSchemas: Array<SchemaObject> = operation.errorSchemas ?? [];

    if (operation.requestSchema !== undefined) {
      requestBody = this.apiAppJsonRequest(operation.requestSchema);
      errorSchemas.push(UnparsableRequestApiProblem);
    }

    if (operation.requestSchema !== undefined || operation.parameters !== undefined) {
      errorSchemas.push(ValidationErrorApiProblem);
    }

    if (this.defaultErrorSchemas.length > 0) {
      errorSchemas.push(...this.defaultErrorSchemas);
    }

    let title = operation.title;
    const descriptionComponents: Array<string> = [];

    if (operation.deprecationMessage !== undefined) {
      descriptionComponents.push(
        `⚠️ **Данный метод API был помечен устаревшим и в будущем будет удалён.** ${operation.deprecationMessage}`,
      );
    }

    if (!operation.isImplemented) {
      title = `⌛️ ${title}`;

      descriptionComponents.push(
        `⌛ **Обратите внимание:** данный метод еще не реализован в API, но будет реализован в будущем.`,
      );
    }

    if (operation.description !== undefined) {
      descriptionComponents.push(operation.description);
    }

    console.info('\x1b[32m \x1b[42m Added operation with ID: \x1b[0m', operation.operationId);

    this.operations[operation.operationId] = {
      method: operation.method,
      operation: {
        deprecated: operation.deprecationMessage !== undefined,
        operationId: operation.operationId,
        summary: title,
        description: descriptionComponents.join('\n\n'),
        parameters: operation.parameters,
        security: this.requiresAuthorization ? AuthorizationSecuritySchema : undefined,
        requestBody: requestBody,
        responses: this.apiResponse(errorSchemas, operation.responseSchema, operation.isRateLimited),
        tags: [operation.tag.name],
      },
    };
  };

  private apiAppJsonRequest = (schema: SchemaObject): RequestBody => {
    return {
      required: true,
      content: {
        'application/json': {
          schema: schema,
        },
      },
    };
  };

  private apiResponse = (
    possibleErrorSchemas: Array<SchemaObject | SchemaReference>,
    responseSchema?: SchemaObject | undefined,
    isRateLimited?: true | undefined,
  ): Responses => {
    const successfulResponse = {
      description: 'Успешный ответ от API.',
      content: {},
      headers: {},
    };
    const errorResponse: Response = {
      description: 'Клиентское приложение или пользователь отправили неправильные данные.',
      content: {},
      headers: {},
    };
    const serverErrorResponse: Response = {
      description: 'Ошибка сервера.',
      content: {
        'application/problem+json': {
          schema: {
            oneOf: [InternalServerErrorApiProblem, ServiceUnavailableApiProblem],
          },
        },
      },
      headers: {},
    };

    if (responseSchema !== undefined) {
      successfulResponse.content = {
        'application/json': {
          schema: responseSchema,
        },
      };
    }

    if (this.requiresAuthorization) {
      possibleErrorSchemas.push(
        AuthorizationHeaderMissingApiProblem,
        InvalidAuthorizationHeaderApiProblem,
        UnknownAccessTokenApiProblem,
        ExpiredAccessTokenApiProblem,
        ProfileDoesNotExistApiProblem,
      );
    }

    if (isRateLimited !== undefined) {
      possibleErrorSchemas.push(TooManyRequestsApiProblem);
    }

    if (possibleErrorSchemas.length > 0) {
      errorResponse.content = {
        'application/problem+json': {
          schema: {
            oneOf: [...possibleErrorSchemas],
          },
        },
      };
    }

    return {
      '200': successfulResponse,
      '400': errorResponse,
      '500': serverErrorResponse,
    };
  };
}
