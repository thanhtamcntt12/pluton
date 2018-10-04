import { Injectable } from '@angular/core';
import {CookieService} from 'angular2-cookie/core';
import { UserService } from '../../../shared/services/user.service';

@Injectable()
export class StaffService extends UserService{
	
	constructor (protected cookieService:CookieService) {
		super()
	}
	
	public save (id: string, email: string, token: string, store_id: string, first_name: string, last_name: string) {		
		this.cookieService.put("staff_token",token);
		this.cookieService.put("staff_store_id", store_id);
		this.cookieService.put("staff_id",id);
		this.cookieService.put("staff_email",email);
		this.cookieService.put("staff_first_name",first_name);
		this.cookieService.put("staff_last_name",last_name);
	}
	
	public getId() : string {
		return this.cookieService.get("staff_id");
	}
	
	public getEmail() : string {
		return this.cookieService.get("staff_email");
	}
		
	public isAuthenticated() : boolean {
		return this.getToken() != null;
	}
    public getStoreID() : string {
        return this.cookieService.get("staff_store_id")
    }
	
	public getFirstName() : string {
        return this.cookieService.get("staff_first_name")
    }
	
	public getLastName() : string {
        return this.cookieService.get("staff_last_name")
    }
		
	public getToken() : string {
		return this.cookieService.get("staff_token");
	}
	
	public removeAll() {
		this.cookieService.remove("staff_id");
		this.cookieService.remove("staff_store_id");
		this.cookieService.remove("staff_email");
		this.cookieService.remove("staff_first_name");
		this.cookieService.remove("staff_last_name");
		this.cookieService.remove("staff_token");
	}
}