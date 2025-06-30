import type { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';

export type TagGroup = {
  name: string;
  tags: Array<string>;
};

export const tagGroup = (name: string, tags: Array<Tag>): TagGroup => {
  return {
    name: name,
    tags: tags.sort((a, b) => a.name.localeCompare(b.name)).map((tag) => tag.name),
  };
};
