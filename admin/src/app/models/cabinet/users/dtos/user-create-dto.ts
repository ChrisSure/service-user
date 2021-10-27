export interface UserCreateDto {
  email: string;
  password: string;
  roles: Array<string>;
  status: string;
}
