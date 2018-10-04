import { Component, OnInit } from '@angular/core';
import { Router,NavigationEnd } from '@angular/router';
import { StaffService } from './shared/services/staff.service';
import { RestfulService } from '../shared/services/restful.service';

declare var $: any;

@Component({
  selector: 'app-staff',
  templateUrl: './staff.component.html',
  styleUrls: ['./staff.component.scss']
})
export class StaffComponent implements OnInit {
	firstName:string;
	lastName:string;
	email:string;

	loadingDone:boolean = false;
	
	constructor(private router: Router, private restfulService: RestfulService, private staffService:StaffService) {
		this.firstName = this.staffService.getFirstName();
		this.lastName = this.staffService.getLastName();
		this.email = this.staffService.getEmail();
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
		
		this.router.navigate(['staff/top']);
	}

	logout() {
		this.restfulService.doPost("staff/logout",{'staff_id':this.staffService.getId()}).subscribe(commonResponse => this.handleResponse(commonResponse));
	}
	
	private handleResponse(commonResponse: any) {
		this.staffService.removeAll();
		this.router.navigate(['staff/login']);
	}
}
