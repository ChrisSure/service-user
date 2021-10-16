import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { CabinetModule } from './cabinet/cabinet.module';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { CabinetGuard } from './cabinet/cabinet.guard';

const routes: Routes = [
  { path: '', component: LoginComponent },
  { path: '**', component: PageNotFoundComponent },
  {
    path: 'cabinet',
    loadChildren: () => CabinetModule,
    canLoad: [CabinetGuard]
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
  providers: [CabinetGuard],
})
export class AppRoutingModule { }
