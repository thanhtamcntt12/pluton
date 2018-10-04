import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-sendmail-confirm',
  templateUrl: './sendmail-confirm.component.html',
  styleUrls: ['./sendmail-confirm.component.scss']
})
export class SendmailConfirmComponent implements OnInit {

  constructor(private router: Router) { }

  ngOnInit() {
  }

}
