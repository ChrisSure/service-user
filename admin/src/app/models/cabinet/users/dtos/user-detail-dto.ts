import { Social } from '../social';
import { Permission } from '../permission';

export interface UserDetailDto {
  email: string;
  id: number;
  roles: Array<string>;
  social: Array<Social>;
  permission: Array<Permission>;
  status: string;
}
