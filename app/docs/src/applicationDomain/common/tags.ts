import { tagGroup, TagGroup } from '../../utils/tagGroup';
import { Tag } from '@fosfad/openapi-typescript-definitions/3.1.0';
import { FileTag } from './operation/file';
import { EmployeeTag } from './operation/employee';
import { AuthTag } from './operation/auth';
import { DepartmentTag } from './operation/department';
import { SurveyTag } from './operation/survey';

export const CommonTags: Array<Tag> = [FileTag, EmployeeTag, AuthTag, DepartmentTag, SurveyTag];
export const CommonTagGroups: Array<TagGroup> = [
  tagGroup('Общее', [FileTag, EmployeeTag, AuthTag, DepartmentTag, SurveyTag]),
];
