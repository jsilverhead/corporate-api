import type { Info, OpenAPI } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { openapiVersion } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { ref } from './utils/ref';
import { LocalServer, NonProductionServer, ProductionServer } from './utils/servers';
import path from 'path';
import fs from 'fs';
import type { TagGroup } from './utils/tagGroup';

export const openapi = async (
  apiVersion: string,
  applicationDomain: string,
): Promise<
  OpenAPI & {
    'x-tagGroups': Array<TagGroup>;
  }
> => {
  const increaseMarkdownHeadersLevel = (markdownText: string, paddingLevel: number): string =>
    markdownText.replace(/^#/gm, '#'.repeat(paddingLevel));

  let description = fs.readFileSync(path.resolve(__dirname, '..', 'README.md')).toString();

  const changelogPath = path.resolve(__dirname, '..', '..', 'CHANGELOG.md');

  if (fs.existsSync(changelogPath)) {
    description += '## История изменений в API';
    description += '\n\n';

    description += increaseMarkdownHeadersLevel(fs.readFileSync(changelogPath).toString(), 3);
  }

  const info: Info & {
    'x-logo': {
      altText: string;
      backgroundColor: string;
      url: string;
    };
  } = {
    title: `Corporate API (${applicationDomain})`,
    version: apiVersion,
    description: description,
    'x-logo': {
      url: '',
      backgroundColor: 'transparent',
      altText: 'Логотип Corporate',
    },
  };

  const { CommonTags, CommonTagGroups } = await import('./applicationDomain/common/tags');
  const commonPath = (await import('./applicationDomain/common/path')).commonOperation.getPaths();

  return {
    openapi: openapiVersion,
    paths: { ...commonPath },
    info: info,
    servers: [NonProductionServer, ProductionServer, LocalServer],
    components: ref.getAllComponents(),
    tags: [...CommonTags].sort((a, b) => a.name.localeCompare(b.name)),
    'x-tagGroups': [...CommonTagGroups],
  };
};
