import { Component, OnInit } from '@angular/core';
import { RestfulService } from '../../shared/services/restful.service';
import { AdminCustomerSearchService } from '../customer-list/searchService';
declare var $: any;

@Component({
  selector: 'app-admin-add',
  templateUrl: './admin-add.component.html',
  styleUrls: ['./admin-add.component.scss']
})
export class AdminAddComponent implements OnInit {

  	private last_name: String;
    private first_name: String;
    private last_name_kana: String;
    private first_name_kana: String;
	private email: String;
	private password: String;
    private note: String;

  	constructor(private restfulService:RestfulService,private searchService: AdminCustomerSearchService) { }

  	ngOnInit() {
  		$(function () {
            $('#form-validation').validate({
                rules:{
                    last_name_kana :{
                        katakana : true
                    },
                    first_name_kana:{
                        katakana : true
                    },
                    last_name:{
                        kana : true
                    },
                    first_name:{
                        kana : true
                    }
                },
                submitHandler: function(form){
                    $("#addConfirm").modal('show');
                    return false;
                }
            });
        });
		this.searchService.name = "";
		this.searchService.fromDate = "";
		this.searchService.toDate = "";
  	}

  	addAdmin (){
        let data = {
                'first_name': this.first_name,
                'last_name': this.last_name,
                'first_name_kana': this.first_name_kana,
                'last_name_kana': this.last_name_kana,
                'email': this.email,
				'password': this.password,
				'note': this.note
			};
        let url = 'admin/add';
        this.restfulService.doPost(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
    }

    private handleResponse(commonResponse: any){
        if(commonResponse.success) {
            $("#addConfirm").modal('hide');
            $("#addedsuccess").modal('show');
        }
        else {        	
            $("#addConfirm").modal('hide');
            alert(commonResponse.error);
        }
    }

}
