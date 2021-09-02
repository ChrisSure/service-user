import { Component, OnInit } from '@angular/core';
import { UserService } from '../../services/cabinet/users/user.servise';
import { User} from '../../models/cabinet/users/user';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {

  public users: Array<User> = [];

  constructor(private userService: UserService) { }

  ngOnInit(): void {
    this.userService.getUsers().subscribe((response) => {
      this.users = response.users;
      console.log(this.users);
    });
  }

}
