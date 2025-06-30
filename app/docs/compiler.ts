import assert from 'assert';
import { promises } from 'fs';
import { join, dirname } from 'path';
import { openapi } from './src/openapi';
import type { OpenAPI } from '@fosfad/openapi-typescript-definitions/3.1.0';

// Usage: ts-node docs/compiler.ts <applicationDomain> <apiVersion> <outputPath>
// Example: ts-node docs/compiler.ts public 1.0.0 build/1.0.0.json

const cliArguments = process.argv.slice(2);

const applicationDomain: string | undefined = cliArguments[0];
const apiVersion: string = cliArguments[1] ?? '0.0.0';
const outputPath: string = cliArguments[2] ?? join(process.cwd(), 'openapi.json');

assert(applicationDomain, 'Application domain (public, admin) is required in 1st argument.');
assert(apiVersion, 'API version is required in 2nd argument.');
assert(outputPath, 'Output path is required in 3rd argument.');

const serializeSpecification = async (specification: Promise<OpenAPI>): Promise<string> =>
  JSON.stringify(await specification, null, 2);

const saveToFile = async (json: string, savePath: string): Promise<void> => {
  await promises.mkdir(dirname(savePath), {
    recursive: true,
  });

  return promises.writeFile(savePath, json);
};

serializeSpecification(openapi(apiVersion, applicationDomain))
  .then((json) => saveToFile(json, outputPath))
  .then(() => console.info(`Documentation for version ${apiVersion} (${applicationDomain}) compiled.`));
