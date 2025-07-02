import { tagGroup, TagGroup } from '../../utils/tagGroup';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { FileTag } from './operation/file';
import { UserTag } from './operation/user';
import { AuthTag } from './operation/auth';

export const CommonTags: Array<Tag> = [FileTag, UserTag, AuthTag];
export const CommonTagGroups: Array<TagGroup> = [tagGroup('Общее', [FileTag, UserTag, AuthTag])];
