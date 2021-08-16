import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Login } from '../models/login/login';
import { LoginService } from '../services/login/login.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  public loginForm = new FormGroup({
    email: new FormControl('', [
      Validators.required,
      Validators.pattern("^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$")
    ]),
    password: new FormControl('', [Validators.required]),
  });

  constructor(private loginService: LoginService) { }

  ngOnInit(): void {

  }

  public onSubmit() {
    const login: Login = {email: this.loginForm.value.email, password: this.loginForm.value.password};
    this.loginService.signIn(login).subscribe((response) => {
        console.log(response);
    });
  }

}
