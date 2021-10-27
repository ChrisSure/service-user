import { Component, OnInit } from '@angular/core';
import { UserService } from '../../../services/cabinet/users/user.servise';
import { UserCreateDto } from '../../../models/cabinet/users/dtos/user-create-dto';
import { roles } from '../../../models/cabinet/users/lists/roles-list';
import { statuses } from '../../../models/common/status/lists/statuses-list';
import { FormControl, FormGroup, Validators } from "@angular/forms";
import { Role } from '../../../models/cabinet/users/role';
import { Status } from '../../../models/common/status/status';
import { MessageTypeEnum } from "../../../models/common/message/enums/message-type-enum";


@Component({
  selector: 'app-user-create',
  templateUrl: './user-create.component.html',
  styleUrls: ['./user-create.component.scss']
})
export class UserCreateComponent implements OnInit {

  public createUserForm = new FormGroup({
    email: new FormControl('', [
      Validators.required,
      Validators.pattern("^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$")
    ]),
    password: new FormControl('', [Validators.required]),
    role: new FormControl('0'),
    status: new FormControl('0'),
  });
  public roles: Array<Role>;
  public statuses: Array<Status>;
  responseMessage: string;
  responseMessageType: string;

  constructor(private userService: UserService) { }

  ngOnInit(): void {
    this.roles = roles;
    this.statuses = statuses;
  }

  public onSubmit(): void {
    const user: UserCreateDto = {
      email: this.createUserForm.value.email,
      password: this.createUserForm.value.password,
      roles: (this.createUserForm.value.role === '0') ? this.roles[0].key : this.createUserForm.value.role,
      status: (this.createUserForm.value.status === '0') ? this.statuses[0].key : this.createUserForm.value.status
    };
    this.userService.createUser(user).subscribe((response) => {
      this.handleMessage(response);
    });
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
