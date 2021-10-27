import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { UserService } from '../../../services/cabinet/users/user.servise';
import { UserDetailDto } from "../../../models/cabinet/users/dtos/user-detail-dto";

@Component({
  selector: 'app-user-detail',
  templateUrl: './user-detail.component.html',
  styleUrls: ['./user-detail.component.scss']
})
export class UserDetailComponent implements OnInit {
  public user: UserDetailDto;

  constructor(private userService: UserService, private route: ActivatedRoute, private router: Router) { }

  ngOnInit(): void {
    const id = this.route.snapshot.params['id'];
    this.userService.getUserById(id).subscribe((response) => {
      this.user = response.user;
    });
  }

  public removeUser(id: number): void {
    this.userService.removeUser(id).subscribe(() => {
      this.router.navigate(['/cabinet/users']).then();
    });
  }

}
