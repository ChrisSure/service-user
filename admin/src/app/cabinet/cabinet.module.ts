import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HomeComponent } from './home/home.component';
import { UsersComponent } from './users/users.component';
import { CabinetComponent } from './cabinet.component';
import { CabinetRoutingModule } from './cabinet-routing.module';
import { HeaderComponent } from './template/header/header.component';
import { SidebarComponent } from './template/sidebar/sidebar.component';

@NgModule({
  declarations: [
    HeaderComponent,
    SidebarComponent,
    HomeComponent,
    CabinetComponent,
    UsersComponent,
  ],
  imports: [
    CommonModule,
    CabinetRoutingModule
  ],
  exports: [CabinetComponent],
  providers: [],
  bootstrap: []
})
export class CabinetModule { }
