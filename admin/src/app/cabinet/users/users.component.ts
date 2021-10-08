import { Component, OnInit } from '@angular/core';
import { UserService } from '../../services/cabinet/users/user.servise';
import { User} from '../../models/cabinet/users/user';
import { FormControl, FormGroup } from '@angular/forms';
import { PaginationService } from '../../services/cabinet/shared/pagination/pagination.service';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {

  public users: Array<User> = [];
  public usersFilterForm = new FormGroup({
    email: new FormControl(''),
    role: new FormControl('0'),
    status: new FormControl('0'),
  });
  public filterQueryString: string = '';

  constructor(public paginationService: PaginationService, private userService: UserService) { }

  ngOnInit(): void {
    this.getUsers();
  }

  private getUsers(): void {
    this.userService.getUsers(this.filterQueryString).subscribe((response) => {
      this.users = response.users;
      this.paginationService.initializaPagination.next(response.totalUsers);
    });
  }

  public onSubmit(): void {
    const email = (this.usersFilterForm.value.email !== '') ? this.usersFilterForm.value.email : null;
    const role = (this.usersFilterForm.value.role !== '0') ? this.usersFilterForm.value.role : null;
    const status = (this.usersFilterForm.value.status !== '0') ? this.usersFilterForm.value.status : null;
    this.filterQueryString = this.createFilterQueryParam(email, role, status);
    this.paginationService.page = 1;
    this.getUsers();
  }

  public clearFilters(): void {
    this.usersFilterForm.patchValue({
      email: '',
      role: '0',
      status: '0'
    });
    this.getUsers();
  }

  public onPreviousPage(): void {
    this.setPageNumber();
    this.getUsers();
  }

  public onNextPage(): void {
    this.setPageNumber();
    this.getUsers();
  }

  private setPageNumber(): void {
    if (this.filterQueryString === '') {
      this.filterQueryString = '?page=' + this.paginationService.page;
    } else {
      this.filterQueryString = this.filterQueryString + '&page=' + this.paginationService.page;
    }
  }

  private createFilterQueryParam(email: string, role: string, status: string): string {
    let filterString = '';
    filterString = (email) ? filterString + 'email=' + email + '&' : filterString;
    filterString = (role) ? filterString + 'role=' + role + '&' : filterString;
    filterString = (status) ? filterString + 'status=' + status + '&' : filterString;
    filterString = (filterString !== '') ? '?' + filterString : filterString;
    filterString = (filterString !== '') ? filterString.substr(0, filterString.length - 1) : filterString;
    return filterString;
  }

}
