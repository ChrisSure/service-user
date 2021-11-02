import { Component, OnInit } from '@angular/core';
import { UserService } from '../../../services/cabinet/users/user.servise';
import { roles } from '../../../models/cabinet/users/lists/roles-list';
import { statuses } from '../../../models/common/status/lists/statuses-list';
import { FormControl, FormGroup, Validators } from "@angular/forms";
import { Role } from '../../../models/cabinet/users/role';
import { Status } from '../../../models/common/status/status';
import { MessageTypeEnum } from "../../../models/common/message/enums/message-type-enum";
import { ActivatedRoute } from '@angular/router';
import { UserEditDto } from '../../../models/cabinet/users/dtos/user-edit-dto';


@Component({
  selector: 'app-user-edit',
  templateUrl: './user-edit.component.html',
  styleUrls: ['./user-edit.component.scss']
})
export class UserEditComponent implements OnInit {

  public editUserForm = new FormGroup({
    email: new FormControl('', [
      Validators.required,
      Validators.pattern("^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$")
    ]),
    role: new FormControl('0'),
    status: new FormControl('0'),
  });
  public roles: Array<Role>;
  public statuses: Array<Status>;
  responseMessage: string;
  responseMessageType: string;
  public user: UserEditDto;
  public id: number;

  constructor(private userService: UserService, private route: ActivatedRoute) { }

  ngOnInit(): void {
    this.roles = roles;
    this.statuses = statuses;
    this.id = this.route.snapshot.params['id'];
    this.userService.getUserById(this.id).subscribe((response) => {
      if (response) {
        this.user = response.user;
        this.fillEditUserForm(response.user);
      }
      console.log(this.user);
    });
  }

  public onSubmit(): void {
    const user: UserEditDto = {
      email: this.editUserForm.value.email,
      roles: (this.editUserForm.value.role === '0') ? this.roles[0].key :
        Array.isArray(this.editUserForm.value.role) ? this.editUserForm.value.role[0] : this.editUserForm.value.role,
      status: (this.editUserForm.value.status === '0') ? this.statuses[0].key : this.editUserForm.value.status
    };
    this.userService.editUser(this.id, user).subscribe((response) => {
      this.handleMessage(response);
    });
  }

  private fillEditUserForm(user: UserEditDto): void {
    this.editUserForm.patchValue({email: user.email, role: user.roles, status: user.status});
  }

  private handleMessage(response: any): void {
    if (response.error) {
      this.responseMessage = response.error;
      this.responseMessageType = MessageTypeEnum.DANGER;
    } else {
      this.responseMessage = response.message;
      this.responseMessageType = MessageTypeEnum.SUCCESS;
    }
  }

}
