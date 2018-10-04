import { Component, OnInit } from '@angular/core';
import { RestfulService } from '../../shared/services/restful.service';
import { StaffService } from '../shared/services/staff.service';
import { Router } from '@angular/router';
	
import 'assets/admin_bsb_material_design/js/admin.js';
import 'assets/admin_bsb_material_design/js/sign-in.js';

declare var $: any;

@Component({
  selector: 'app-staff-login',
  templateUrl: './staff-login.component.html',
  styleUrls: ['./staff-login.component.scss']
})
export class StaffLoginComponent implements OnInit {
	model: any = {};

	constructor(private router: Router, private restfulService: RestfulService, private staffService: StaffService) { }

	ngOnInit() {
	}

	login() {
		if($("#sign_in").valid())
			this.restfulService.doPost("staff/login",{'email':this.model.email,'password':this.model.password}).subscribe(commonResponse => this.handleResponse(commonResponse));
	}
	
	private handleResponse(commonResponse: any) {
		if(commonResponse.success) {
			this.staffService.save(commonResponse.data.id, this.model.email,commonResponse.data.api_token, commonResponse.data.store_id, commonResponse.data.first_name, commonResponse.data.last_name);
			this.router.navigate(['staff/top']);
		}
		else {
			alert("Login fail!");
		}
	}
}
