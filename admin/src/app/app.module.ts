import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { CabinetModule } from './cabinet/cabinet.module';
import { LoginComponent } from './login/login.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { HttpClientModule } from '@angular/common/http';
import { LoginService } from './services/login/login.service';
import { ReactiveFormsModule } from '@angular/forms';
import { TokenService } from './services/token/token.service';
import { UserService } from './services/cabinet/users/user.servise';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    PageNotFoundComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    ReactiveFormsModule,
    CabinetModule,
    AppRoutingModule
  ],
  providers: [
    LoginService,
    TokenService,
    UserService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
