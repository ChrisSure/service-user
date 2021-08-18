import { Injectable } from '@angular/core';
import jwt_decode from 'jwt-decode';
import { Token } from '../../models/token/token';

@Injectable({
  providedIn: 'root'
})

export class TokenService {

  constructor() {

  }

  public get isAuth(): boolean {
    let token = localStorage.getItem('token');
    let roles = localStorage.getItem('roles');
    let result: boolean;

    if (token && (roles === 'ROLE_ADMIN' || roles === 'ROLE_SUPER_ADMIN'))
      result = true;
    else
      result = false;
    return result;
  }

  public writeToken(jwtToken: string): void {
    const tokenData: Token = jwt_decode(jwtToken);
    localStorage.setItem('token', jwtToken);
    localStorage.setItem('id', tokenData.id);
    localStorage.setItem('email', tokenData.email);
    localStorage.setItem('exp', tokenData.exp);
    localStorage.setItem('iat', tokenData.iat);
    localStorage.setItem('roles', tokenData.roles);
    console.log(tokenData);
  }

  public deleteToken(): void {
    localStorage.removeItem('token');
    localStorage.removeItem('id');
    localStorage.removeItem('email');
    localStorage.removeItem('exp');
    localStorage.removeItem('iat');
    localStorage.removeItem('roles');
  }

}
