import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HomeComponent } from './home/home.component';
import { UsersComponent } from './users/users.component';
import { CabinetComponent } from './cabinet.component';
import { CabinetRoutingModule } from './cabinet-routing.module';
import { HeaderComponent } from './template/header/header.component';
import { SidebarComponent } from './template/sidebar/sidebar.component';
import { ReactiveFormsModule } from '@angular/forms';
import { PaginationService } from '../services/cabinet/shared/pagination/pagination.service';
import { PaginationComponent } from './shared/pagination/pagination.component';
import { UserDetailComponent } from './users/user-detail/user-detail.component';

@NgModule({
  declarations: [
    HeaderComponent,
    SidebarComponent,
    HomeComponent,
    CabinetComponent,
    UsersComponent,
    PaginationComponent,
    UserDetailComponent
  ],
  imports: [
    CommonModule,
    CabinetRoutingModule,
    ReactiveFormsModule,
  ],
  exports: [CabinetComponent],
  providers: [
    PaginationService
  ],
  bootstrap: []
})
export class CabinetModule { }
