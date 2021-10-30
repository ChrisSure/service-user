import { Component, OnInit } from '@angular/core';
import { UserService } from '../../../services/cabinet/users/user.servise';
import { AbstractControl, FormControl, FormGroup, Validators } from '@angular/forms';
import { MessageTypeEnum } from '../../../models/common/message/enums/message-type-enum';
import { ActivatedRoute } from '@angular/router';
import { UserChangePasswordDto } from '../../../models/cabinet/users/dtos/user-change-password-dto';


@Component({
  selector: 'app-user-change-password',
  templateUrl: './user-change-password.component.html',
  styleUrls: ['./user-change-password.component.scss']
})
export class UserChangePasswordComponent implements OnInit {

  public changePasswordUserForm = new FormGroup({
    password: new FormControl('', [Validators.required]),
    confirmPassword: new FormControl('', Validators.required),
  }, { validators: this.comparePassword });
  responseMessage: string;
  responseMessageType: string;
  public id: number;

  constructor(private userService: UserService, private route: ActivatedRoute) { }

  ngOnInit(): void {
    this.id = this.route.snapshot.params['id'];
  }

  public onSubmit(): void {
    const password: UserChangePasswordDto = {
      password: this.changePasswordUserForm.value.password
    }
    this.userService.changePasswordUser(this.id, password).subscribe((response) => {
      this.handleMessage(response);
    });
  }

  private comparePassword(control: AbstractControl): {[key: string]: any} | null  {
    if (control.value.password !== control.value.confirmPassword) {
      return {notSame: true};
    }
    return null;
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
