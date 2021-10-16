import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Login } from '../models/login/login';
import { LoginService } from '../services/login/login.service';
import { TokenService } from '../services/token/token.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  public loginError = false;
  public loginApiMessage = '';

  public loginForm = new FormGroup({
    email: new FormControl('', [
      Validators.required,
      Validators.pattern("^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$")
    ]),
    password: new FormControl('', [Validators.required]),
  });

  constructor(private loginService: LoginService, private tokenService: TokenService, private router: Router) { }

  ngOnInit(): void {
    if (this.tokenService.isAuth) {
      this.router.navigate(['/cabinet']).then();
    }
  }

  public onSubmit() {
    const login: Login = {email: this.loginForm.value.email, password: this.loginForm.value.password};
    this.loginService.signIn(login).subscribe((response) => {
      if (response) {
        if (response.error) {
          this.loginError = true;
          this.loginApiMessage = response.error;
        } else {
          this.loginError = false;
          this.tokenService.writeToken(response.token);
          this.router.navigate(['/cabinet']).then();
        }
      }
    });
  }

}
