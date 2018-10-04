import { Component, OnInit } from '@angular/core';
import { RestfulService } from '../../shared/services/restful.service';
import { AdminService } from '../shared/services/admin.service';
import { Router } from '@angular/router';

import 'assets/admin_bsb_material_design/js/admin.js';
import 'assets/admin_bsb_material_design/js/sign-in.js';

declare var $: any;

@Component({
  selector: 'app-admin-login',
  templateUrl: './admin-login.component.html',
  styleUrls: ['./admin-login.component.scss']
})
export class AdminLoginComponent implements OnInit {
	model: any = {};

	constructor(private router: Router, private restfulService: RestfulService, private adminService: AdminService) { }

	ngOnInit() {
	}

	login() {
		if($("#sign_in").valid())
			this.restfulService.doPost("admin/login",{'email':this.model.email,'password':this.model.password}).subscribe(commonResponse => this.handleResponse(commonResponse));
	}
	
	private handleResponse(commonResponse: any) {
		if(commonResponse.success) {
			this.adminService.save(commonResponse.data.id, this.model.email,commonResponse.data.api_token, commonResponse.data.first_name, commonResponse.data.last_name);           
			this.router.navigate(['admin/top']);
		}
		else {
			alert("Login fail!");
		}
	}
}
