import type {
  Components,
  Header,
  Parameter,
  RequestBody,
  Response,
  Schema,
  SchemaObject,
  SecurityScheme,
} from '@fosfad/openapi-typescript-definitions/3.1.0';

export type SchemaReference = { $ref: string };

class ReferableComponents {
  private schemas: { [key: string]: SchemaObject } = {};

  private responses: { [key: string]: Response } = {};

  private parameters: { [key: string]: Parameter } = {};

  private requestBodies: { [key: string]: RequestBody } = {};

  private headers: { [key: string]: Header } = {};

  private securitySchemes: { [key: string]: SecurityScheme } = {};

  public schema = (key: string, schema: SchemaObject): SchemaReference => {
    schema.title = key;

    const componentPointer = `#/components/schemas/${key}`;

    if (this.schemas[key] !== undefined) {
      throw new Error(`Duplicate component name: ${componentPointer}`);
    }

    this.schemas[key] = schema;

    return {
      $ref: componentPointer,
    };
  };

  public response = (key: string, response: Response): SchemaReference => {
    const componentPointer = `#/components/responses/${key}`;

    if (this.responses[key] !== undefined) {
      throw new Error(`Duplicate component name: ${componentPointer}`);
    }

    this.responses[key] = response;

    return {
      $ref: componentPointer,
    };
  };

  public parameter = (key: string, parameter: Parameter): SchemaReference => {
    const componentPointer = `#/components/parameters/${key}`;

    if (this.parameters[key] !== undefined) {
      throw new Error(`Duplicate component name: ${componentPointer}`);
    }

    this.parameters[key] = parameter;

    return {
      $ref: componentPointer,
    };
  };

  public header = (key: string, header: Header): SchemaReference => {
    const componentPointer = `#/components/headers/${key}`;

    if (this.headers[key] !== undefined) {
      throw new Error(`Duplicate component name: ${componentPointer}`);
    }

    this.headers[key] = header;

    return {
      $ref: componentPointer,
    };
  };

  public requestBody = (key: string, requestBody: RequestBody): SchemaReference => {
    const componentPointer = `#/components/requestBodies/${key}`;

    if (this.requestBodies[key] !== undefined) {
      throw new Error(`Duplicate component name: ${componentPointer}`);
    }

    this.requestBodies[key] = requestBody;

    return {
      $ref: componentPointer,
    };
  };

  public requestAppJson = (key: string, schema: Schema): SchemaReference => {
    return this.requestBody(key, {
      content: {
        'application/json': {
          schema: schema,
        },
      },
    });
  };

  public securityScheme = (key: string, securityScheme: SecurityScheme): SchemaReference => {
    const componentPointer = `#/components/securitySchemes/${key}`;

    if (this.securitySchemes[key] !== undefined) {
      throw new Error(`Duplicate component name: ${componentPointer}`);
    }

    this.securitySchemes[key] = securityScheme;

    return {
      $ref: componentPointer,
    };
  };

  public getAllComponents = (): Components => {
    return {
      schemas: this.schemas,
      responses: this.responses,
      parameters: this.parameters,
      requestBodies: this.requestBodies,
      headers: this.headers,
      securitySchemes: this.securitySchemes,
    };
  };
}

export const ref = new ReferableComponents();
