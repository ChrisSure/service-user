import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, Router, Route, CanLoad } from '@angular/router';
import { Injectable } from '@angular/core';
import { TokenService } from '../services/token/token.service';

@Injectable()
export class CabinetGuard implements CanActivate, CanLoad {
  constructor(private router: Router, private tokenService: TokenService) { }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
    if (this.tokenService.isAuth) {
      return true;
    }
    this.router.navigate(['/']).then();
    return false;
  }

  canLoad(route: Route): boolean {
    if (this.tokenService.isAuth) {
      return true;
    }
    this.router.navigate(['/']).then();
    return false;
  }

}
