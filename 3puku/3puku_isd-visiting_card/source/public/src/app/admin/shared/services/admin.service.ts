import { Injectable } from '@angular/core';
import {CookieService} from 'angular2-cookie/core';
import { UserService } from '../../../shared/services/user.service';

@Injectable()
export class AdminService extends UserService{
	
	constructor (protected cookieService:CookieService) {
		super()
	}
	
	public save (id: string, email: string, token: string, first_name: string, last_name: string) {		
		this.cookieService.put("admin_token",token);
		this.cookieService.put("admin_id",id);
		this.cookieService.put("admin_email",email);
		this.cookieService.put("admin_first_name",first_name);
		this.cookieService.put("admin_last_name",last_name);
	}
	
	public getId() : string {
		return this.cookieService.get("admin_id");
	}
	
	public getEmail() : string {
		return this.cookieService.get("admin_email");
	}
		
	public isAuthenticated() : boolean {
		return this.getToken() != null;
	}
	
	public getFirstName() : string {
        return this.cookieService.get("admin_first_name")
    }
	
	public getLastName() : string {
        return this.cookieService.get("admin_last_name")
    }
		
	public getToken() : string {
		return this.cookieService.get("admin_token");
	}
	
	public removeAll() {
		this.cookieService.remove("admin_id");
		this.cookieService.remove("admin_email");
		this.cookieService.remove("admin_first_name");
		this.cookieService.remove("admin_last_name");
		this.cookieService.remove("admin_token");
	}
}