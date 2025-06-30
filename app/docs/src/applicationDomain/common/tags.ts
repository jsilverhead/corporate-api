import { tagGroup, TagGroup } from '../../utils/tagGroup';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { FileTag } from './operation/file';

export const CommonTags: Array<Tag> = [FileTag];
export const CommonTagGroups: Array<TagGroup> = [tagGroup('Общее', [FileTag])];
