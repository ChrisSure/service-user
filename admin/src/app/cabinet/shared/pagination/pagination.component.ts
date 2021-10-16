import {Component, OnInit, Input, Output, EventEmitter} from '@angular/core';
import { PaginationService } from '../../../services/cabinet/shared/pagination/pagination.service';

@Component({
  selector: 'app-pagination',
  templateUrl: './pagination.component.html',
  styleUrls: ['./pagination.component.scss']
})
export class PaginationComponent implements OnInit {
  @Input() model: any;
  @Output() previousPage = new EventEmitter<string>();
  @Output() nextPage = new EventEmitter<string>();

  constructor(public paginationService: PaginationService) { }

  ngOnInit(): void {
    this.paginationService.initializaPagination.subscribe((totalUsers) => {
      this.paginationService.setTotalUsers(totalUsers);
      this.paginationService.setMaxPages();
    });
  }

  public onPreviousPage(): void {
    this.paginationService.setPreviousPage();
    this.previousPage.emit('previousPage');
  }

  public onNextPage(): void {
    this.paginationService.setNextPage();
    this.nextPage.emit('nextPage');
  }



}
