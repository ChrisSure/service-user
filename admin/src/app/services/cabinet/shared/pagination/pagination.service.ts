import { Injectable } from '@angular/core';
import {BehaviorSubject} from "rxjs";

@Injectable({
  providedIn: 'root'
})

export class PaginationService {
  private _page: number = 1;
  public totalUsers: number = 0;
  public maxPages: number = 0;
  public perPage: number = 1;
  public initializaPagination: BehaviorSubject<any> = new BehaviorSubject(null);

  public get page(): number {
    return this._page;
  }

  public set page(page: number) {
    this._page = page;
  }

  public setTotalUsers(totalUsers: number): void {
    this.totalUsers = totalUsers;
  }

  public setMaxPages(): void {
    this.maxPages = Math.ceil(this.totalUsers / this.perPage);
  }

  public setPreviousPage(): void {
    this.page = this.page - 1;
  }

  public setNextPage(): void {
    this.page = this.page + 1;
  }



}
