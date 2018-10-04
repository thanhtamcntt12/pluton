import { Component, OnInit } from '@angular/core';
import { Router,NavigationEnd } from '@angular/router';
import { AdvisorService } from './shared/services/advisor.service';
import { RestfulService } from '../shared/services/restful.service';

declare var $: any;

@Component({
  selector: 'app-advisor',
  templateUrl: './advisor.component.html',
  styleUrls: ['./advisor.component.scss']
})
export class AdvisorComponent implements OnInit {
	firstName:string;
	lastName:string;
	email:string;
	
	loadingDone:boolean = false;
	
	constructor(private router: Router, private restfulService: RestfulService, private advisorService:AdvisorService) {
		this.firstName = this.advisorService.getFirstName();
		this.lastName = this.advisorService.getLastName();
		this.email = this.advisorService.getEmail();
	}

	ngOnInit() {
		this.loadingDone = true;
			
		//
		$.AdminBSB.browser.activate();
		$.AdminBSB.leftSideBar.activate();
		$.AdminBSB.navbar.activate();
		$.AdminBSB.dropdownMenu.activate();
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
		$.AdminBSB.search.activate();
		this.router.navigate(['advisor/top']);
		
		setTimeout(function () { $('.page-loader-wrapper').fadeOut(); }, 50);
	}

	logout() {
		this.restfulService.doPost("advisor/logout",{'advisor_id':this.advisorService.getId()}).subscribe(commonResponse => this.handleResponse(commonResponse));
	}
	
	private handleResponse(commonResponse: any) {
		this.advisorService.removeAll();
		this.router.navigate(['advisor/login']);
	}

}
