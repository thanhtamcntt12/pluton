import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions, URLSearchParams} from '@angular/http';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/map';

import { environment } from '../../../environments/environment';
import { AdminService } from '../../admin/shared/services/admin.service';
import { StaffService } from '../../staff/shared/services/staff.service';
import { AdvisorService } from '../../advisor/shared/services/advisor.service';

@Injectable()
export class RestfulService {
	constructor (private http: Http, private adminService: AdminService, private staffService: StaffService, private advisorService: AdvisorService) {
	
	}
	
	private extractData(res: Response) {
		return res.json();
	}
	
	private handleError (error: Response | any){
		let errMsg: string;
		if (error instanceof Response) {
			const body = error.json() || '';
			const err = body.error || JSON.stringify(body);
			errMsg = `${error.status} - ${error.statusText || ''} ${err}`;
		} else {
			errMsg = error.message ? error.message : error.toString();
		}
		console.error(errMsg);
		return Observable.throw(errMsg);
	}

	public doGet(url : string, data : any): Observable<CommonResponse> {
        let headers      = new Headers({ 'Content-Type': 'application/json' });
        let params = new URLSearchParams();
        for(let key in data) {
            params.set(key, data[key]);
        }
        params.set('api_token',this.getToken(url));
        let options       = new RequestOptions({ headers: headers, search: params });
        return this.http.get(environment.API_ENDPOINT+url, options)
            .map(this.extractData)
            .catch(this.handleError);
	}
	
    public doPut(url : string, data : any): Observable<CommonResponse> {
        let headers      = new Headers({ 'Content-Type': 'application/json' });
        let options       = new RequestOptions({ headers: headers });
        data.api_token = this.getToken(url);
        return this.http.put(environment.API_ENDPOINT+url,data, options)
            .map(this.extractData)
            .catch(this.handleError);
    }
	
	public doPost(url : string, data : any): Observable<CommonResponse> {
        let headers      = new Headers({ 'Content-Type': 'application/json' }); 
        let options       = new RequestOptions({ headers: headers }); 
		
		data.api_token = this.getToken(url);

		return this.http.post(environment.API_ENDPOINT+url,data, options)
							.map(this.extractData)
							.catch(this.handleError);
	}
	
	private getToken(url: string): string {
		let type = url.split("/")[0];
		
		if(type == "staff") {
			return this.staffService.getToken();
		}
		else if(type == "admin") {
			return this.adminService.getToken();
		}else if(type == "advisor") {
			return this.advisorService.getToken();
		}
		else {
			return null;
		}
	}
}

export class CommonResponse {
	success: boolean ;
	data: any;
	error: string;
}