import { tagGroup, TagGroup } from '../../utils/tagGroup';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { FileTag } from './operation/file';
import { UserTag } from './operation/user';
import { AuthTag } from './operation/auth';
import { DepartmentTag } from './operation/department';

export const CommonTags: Array<Tag> = [FileTag, UserTag, AuthTag, DepartmentTag];
export const CommonTagGroups: Array<TagGroup> = [tagGroup('Общее', [FileTag, UserTag, AuthTag, DepartmentTag])];
