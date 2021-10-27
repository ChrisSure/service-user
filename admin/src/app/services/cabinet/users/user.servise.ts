import { Injectable } from '@angular/core';
import {HttpClient, HttpErrorResponse, HttpHeaders} from '@angular/common/http';
import {Observable} from "rxjs";
import {catchError} from "rxjs/operators";
import {TokenService} from "../../token/token.service";
import {UserCreateDto} from "../../../models/cabinet/users/dtos/user-create-dto";

@Injectable({
  providedIn: 'root'
})

export class UserService {
  private baseUrl: string;
  private headers: HttpHeaders;

  constructor(private http: HttpClient, private tokenService: TokenService) {
    this.baseUrl = 'http://localhost:9999/users/';
    this.headers = new HttpHeaders({'Content-Type': 'application/json', 'Authorization': `Bearer ${this.tokenService.getToken()}`});
  }

  public getUsers(filterString?: string): Observable<any> {
    const url = (filterString) ? (this.baseUrl + filterString) : this.baseUrl;
    return this.http.get(url, { headers: this.headers })
      .pipe(catchError(this.error));
  }

  public getUserById(id: number): Observable<any> {
    return this.http.get(this.baseUrl + id, { headers: this.headers })
      .pipe(catchError(this.error));
  }

  public createUser(user: UserCreateDto): Observable<any> {
    return this.http.post(this.baseUrl, user, { headers: this.headers })
      .pipe(catchError(this.error));
  }

  public removeUser(id: number): Observable<any> {
    return this.http.delete(this.baseUrl + id, { headers: this.headers })
      .pipe(catchError(this.error));
  }

  error(error: HttpErrorResponse): Observable<any> {
    return new Observable<any>(observer => {
      observer.next(error.error);
    });
  }

}
