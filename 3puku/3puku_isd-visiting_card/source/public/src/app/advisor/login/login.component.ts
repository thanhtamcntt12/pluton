import { Component, OnInit } from '@angular/core';
import { RestfulService } from '../../shared/services/restful.service';
import { AdvisorService } from '../shared/services/advisor.service';
import { Router } from '@angular/router';

import 'assets/admin_bsb_material_design/js/admin.js';
import 'assets/admin_bsb_material_design/js/sign-in.js';

declare var $: any;

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class AdvisorLoginComponent implements OnInit {
	model: any = {};

	constructor(private router: Router, private restfulService: RestfulService, private advisorService: AdvisorService) { }

	ngOnInit() {
	}

	login() {
		if($("#sign_in").valid())
			this.restfulService.doPost("advisor/login",{'email':this.model.email,'password':this.model.password}).subscribe(commonResponse => this.handleResponse(commonResponse));
	}
	
	private handleResponse(commonResponse: any) {
		if(commonResponse.success) {
			this.advisorService.save(commonResponse.data.id, this.model.email,commonResponse.data.api_token, commonResponse.data.first_name, commonResponse.data.last_name);           
			this.router.navigate(['advisor/top']);
		}
		else {
			alert("Login fail!");
		}
	}
}
