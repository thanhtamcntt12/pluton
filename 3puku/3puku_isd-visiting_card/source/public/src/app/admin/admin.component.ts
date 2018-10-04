import { Component, OnInit } from '@angular/core';
import { Router,NavigationEnd } from '@angular/router';
import { AdminService } from './shared/services/admin.service';
import { RestfulService } from '../shared/services/restful.service';

declare var $: any;

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.scss']
})
export class AdminComponent implements OnInit {
	firstName:string;
	lastName:string;
	email:string;
	
	loadingDone:boolean = false;
	
	constructor(private router: Router, private restfulService: RestfulService, private adminService:AdminService) {
		this.firstName = this.adminService.getFirstName();
		this.lastName = this.adminService.getLastName();
		this.email = this.adminService.getEmail();
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
		this.router.navigate(['admin/top']);

		setTimeout(function () { $('.page-loader-wrapper').fadeOut(); }, 50);
	}

	logout() {
		this.restfulService.doPost("admin/logout",{'admin_id':this.adminService.getId()}).subscribe(commonResponse => this.handleResponse(commonResponse));
	}
	
	private handleResponse(commonResponse: any) {
		this.adminService.removeAll();
		this.router.navigate(['admin/login']);
	}
}
