import { Component, OnInit } from '@angular/core';
import {IMyDpOptions} from 'mydatepicker';
import { RestfulService } from '../../shared/services/restful.service';
import { StaffService } from '../shared/services/staff.service';
import { StaffCustomerSearchService } from '../customer-list/searchService';
declare var $: any;

@Component({
  selector: 'app-customer-add',
  templateUrl: './customer-add.component.html',
  styleUrls: ['./customer-add.component.scss']
})
export class CustomerAddComponent implements OnInit {
	private myDatePickerOptions: IMyDpOptions = {
        // other options...
        dateFormat: 'yyyy/mm/dd',
    };

    private model = { birthday: null};
    private last_name: String;
    private first_name: String;
    private last_name_kana: String;
    private first_name_kana: String;
    private note1: String;
	constructor(private staffService: StaffService,
                private restfulService: RestfulService,
				private searchService: StaffCustomerSearchService) { }

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
		this.searchService.name = "";
		this.searchService.fromDate = "";
		this.searchService.toDate = "";
	}
	
    addCustomer(){
        let data={
            'params':{
                'store_id':this.staffService.getStoreID(),
                'first_name': this.first_name,
                'last_name': this.last_name,
                'first_name_kana': this.first_name_kana,
                'last_name_kana': this.last_name_kana,
                'birthday':  this.model.birthday ? this.model.birthday.date.year + '-' + this.model.birthday.date.month + '-' + this.model.birthday.date.day : '',
                'note1': this.note1
            }
        };
        let url = 'staff/customer/add';
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
	
	confirm() {
		if(!$("#form-validation").valid()) return;
		
		let birthday_input = $("#birthday input").val();
		if(birthday_input.includes("/"))
			this.model.birthday = { date: {year: parseInt(birthday_input.slice(0, 4)), month: parseInt(birthday_input.slice(5,7)), day: parseInt(birthday_input.slice(8)) } };
		else
			this.model.birthday = { date: {year: parseInt(birthday_input.slice(0,4)), month: parseInt(birthday_input.slice(4,6)), day: parseInt(birthday_input.slice(6)) } };
	
	}


}
