import { Component, OnInit } from '@angular/core';
import {IMyDpOptions} from 'mydatepicker';
import { RestfulService } from '../../shared/services/restful.service';
import { AdminCustomerSearchService } from '../customer-list/searchService';
declare var $: any;

@Component({
  selector: 'app-staff-add',
  templateUrl: './staff-add.component.html',
  styleUrls: ['./staff-add.component.scss']
})
export class StaffAddComponent implements OnInit {
	private myDatePickerOptions:IMyDpOptions = {
        // other options...
        dateFormat: 'yyyy/mm/dd',
    };
	
	private stores:any;
	model = { birthday: null};
	private last_name: String;
    private first_name: String;
    private last_name_kana: String;
    private first_name_kana: String;
	private store_id= '';
	private store_name: string;
	private email: String;
	private password: String;
    private note: String;
	
	
	constructor(private restfulService:RestfulService, private searchService: AdminCustomerSearchService) { }

	ngOnInit() {
        $(function () {
            setTimeout(
                function()
                {
                    $('#birthday input').prop('required',true);
					$( "#birthday input" ).addClass( "date_format" );
                }, 500);
				
				$.validator.addClassRules("date_format", {
					date_format: true,
					date_invalid: true,
				});
				
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
                    },
                    birthday:{
                        require: true
                    }
                },
                submitHandler: function(form){
                    $("#addConfirm").modal('show');
                    
                    return false;
                }
            });
        });
		this.getListStore();
		
		this.searchService.name = "";
		this.searchService.fromDate = "";
		this.searchService.toDate = "";
	}
	
	private getListStore(){
		let url = 'admin/store/list';
        this.restfulService.doGet(url,null).subscribe(commonResponse => this.handleResponseStore(commonResponse));
	}
	
	private handleResponseStore(commonResponse:any) {
        if (commonResponse.success) {
            this.stores = commonResponse.data.data;
        } else {
            alert(commonResponse.error);
        }
    }
	
	confirm() {
		if(!$("#form-validation").valid()) return;
		
		this.store_name = this.findStoreName(this.store_id);
		let birthday_input = $("#birthday input").val();
		if(birthday_input.includes("/"))
			this.model.birthday = { date: {year: parseInt(birthday_input.slice(0, 4)), month: parseInt(birthday_input.slice(5,7)), day: parseInt(birthday_input.slice(8)) } };
		else
			this.model.birthday = { date: {year: parseInt(birthday_input.slice(0,4)), month: parseInt(birthday_input.slice(4,6)), day: parseInt(birthday_input.slice(6)) } };
	
	}
	
	addStaff (){
		
        let data = {
                'first_name': this.first_name,
                'last_name': this.last_name,
                'first_name_kana': this.first_name_kana,
                'last_name_kana': this.last_name_kana,
                'birthday':  this.model.birthday ? this.model.birthday.date.year + '-' + this.model.birthday.date.month + '-' + this.model.birthday.date.day : '',
                'email': this.email,
				'password': this.password,
				'store_id':this.store_id,
				'note': this.note
			};
        let url = 'admin/staff/add';
        this.restfulService.doPost(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
    }

    private handleResponse(commonResponse: any){
        if(commonResponse.success) {
            $("#addConfirm").modal('hide');
            $("#addedsuccess").modal('show');
        }
        else {
            alert(commonResponse.error);
        }
    }
	
	private findStoreName(store_id){
		for (var i=0; i < this.stores.length; i++) {
			if (this.stores[i].id == store_id) {
				return this.stores[i].store_name;
			}
		}
	}
	
}
