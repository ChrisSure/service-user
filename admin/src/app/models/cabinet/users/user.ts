import { Social } from './social';

export interface User {
  email: string;
  id: number;
  roles: Array<string>;
  social: Array<Social>;
  status: string;
  token?: string;
  username?: string
}
