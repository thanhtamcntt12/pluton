import { Injectable } from '@angular/core';
import {CookieService} from 'angular2-cookie/core';
import { UserService } from '../../../shared/services/user.service';

@Injectable()
export class AdvisorService extends UserService{
	
	constructor (protected cookieService:CookieService) {
		super()
	}
	
	public save (id: string, email: string, token: string, first_name: string, last_name: string) {		
		this.cookieService.put("advisor_token",token);
		this.cookieService.put("advisor_id",id);
		this.cookieService.put("advisor_email",email);
		this.cookieService.put("advisor_first_name",first_name);
		this.cookieService.put("advisor_last_name",last_name);
	}
	
	public getId() : string {
		return this.cookieService.get("advisor_id");
	}
	
	public getEmail() : string {
		return this.cookieService.get("advisor_email");
	}
		
	public isAuthenticated() : boolean {
		return this.getToken() != null;
	}
	
	public getFirstName() : string {
        return this.cookieService.get("advisor_first_name")
    }
	
	public getLastName() : string {
        return this.cookieService.get("advisor_last_name")
    }
		
	public getToken() : string {
		return this.cookieService.get("advisor_token");
	}
	
	public removeAll() {
		this.cookieService.remove("advisor_id");
		this.cookieService.remove("advisor_email");
		this.cookieService.remove("advisor_first_name");
		this.cookieService.remove("advisor_last_name");
		this.cookieService.remove("advisor_token");
	}
}