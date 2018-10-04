import { Component, OnInit } from '@angular/core';
import {IMyDpOptions} from 'mydatepicker';
import { RestfulService } from '../../shared/services/restful.service';
import { StaffService } from '../shared/services/staff.service';
import { ActivatedRoute, Router  } from '@angular/router';
import * as moment from 'moment/moment';
declare var $: any;

@Component({
  selector: 'app-customer-view',
  templateUrl: './customer-view.component.html',
  styleUrls: ['./customer-view.component.scss']
})
export class CustomerViewComponent implements OnInit {
	private myDatePickerOptions: IMyDpOptions = {
        // other options...
        dateFormat: 'yyyy/mm/dd',
    };
	// Initialized to specific date (09.10.2018).
    private model = { birthday: null, status:'',created_at: null};
    private customer_id: number;
	private compatibilities: any;
    private customer= {last_name_kana: '', first_name_kana: '',
                        last_name: '', first_name: '',
                        note1:'',note2:'',note3:'', birthday: null,created_at: null};
    private data: any;
    private status=['初回来店','追客中','申込','取止','他決','断り'];
    constructor(private restfulService:RestfulService,
                private staffService:StaffService,
                private activeRoute: ActivatedRoute,
                private route: Router,
                ) {
    }
	
	ngOnInit() {
        $(function () {
            setTimeout(
                function()
                {
                    $('#birthday input').attr('name', 'input_birthday');
                    $('#birthday input').prop('required',true);
					$("#birthday input").addClass( "date_format" );

                    $('#created_at input').attr('name', 'input_created_at');
					$('#created_at input').prop('required',true);
					$("#created_at input").addClass( "date_format" );
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
					,
					created_at:{
                        require: true
                    }
                },
                submitHandler: function(form){
                    $("#editCustomer").modal('hide');
                    $("#editConfirm").modal('show');
                    return false;
                }
            });
        });
        this.activeRoute.params.subscribe(params => {
            this.customer_id = params['id'];
        });
        this.getCustomerInfo();
	}

    /*
     * update customer
     */
    updateCustomer(){
        let params = {'params':{
            'id': this.customer_id,
            'first_name': this.customer.first_name,
            'last_name': this.customer.last_name,
            'first_name_kana': this.customer.first_name_kana,
            'last_name_kana': this.customer.last_name_kana,
            'note1': this.customer.note1,
			'note2': this.customer.note2,
			'note3': this.customer.note3,
            'birthday': this.model.birthday.date.year + '-' + this.model.birthday.date.month + '-' + this.model.birthday.date.day,
			'created_at': this.model.created_at.date.year + '-' + this.model.created_at.date.month + '-' + this.model.created_at.date.day
        }};
        let url = 'staff/customer/update';
        this.restfulService.doPost(url, params).subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
    }
    /*
     * get customer info
     */
    getCustomerInfo(){
        let params = {'id': this.customer_id};
        let url = 'staff/customer/detail_list';
        this.restfulService.doGet(url, params).subscribe(commonResponse => this.handleResponse(commonResponse));
    }
    /*
     * remove customer
     */
    removeCustomer(){
        let params = {'id': this.customer_id};
        let url = 'staff/customer/del';
        this.restfulService.doPut(url, params).subscribe(commonResponse => this.handleResponseRemove(commonResponse));
    }

    /*
     *customer detail
     */
    getDetail(){
        let params = {'id': this.customer_id};
        let url = 'staff/customer/detail';
        this.restfulService.doGet(url, params).subscribe(commonResponse => this.handleResponseDetail(commonResponse));
    }
	
	confirm() {
		if(!$("#form-validation").valid()) return;
		
		let birthday_input = $("#birthday input").val();
		if(birthday_input.includes("/"))
			this.model.birthday = { date: {year: parseInt(birthday_input.slice(0, 4)), month: parseInt(birthday_input.slice(5,7)), day: parseInt(birthday_input.slice(8)) } };
		else
			this.model.birthday = { date: {year: parseInt(birthday_input.slice(0,4)), month: parseInt(birthday_input.slice(4,6)), day: parseInt(birthday_input.slice(6)) } };
		
		let created_at_input = $("#created_at input").val();
		if(created_at_input.includes("/"))
			this.model.created_at = { date: {year: parseInt(created_at_input.slice(0, 4)), month: parseInt(created_at_input.slice(5,7)), day: parseInt(created_at_input.slice(8)) } };
		else
			this.model.created_at = { date: {year: parseInt(created_at_input.slice(0,4)), month: parseInt(created_at_input.slice(4,6)), day: parseInt(created_at_input.slice(6)) } };
	
	}
    updateStatus(){
        let params = {'id': this.customer_id, 'status': this.model.status};
        let url = 'staff/customer/status';
        this.restfulService.doPut(url, params).subscribe(commonResponse => this.handleResponseUpdateStatus(commonResponse));
    }
    /*
     * update staff in charge
     */
    updateStaffInCharge(comp){
        let params = {'id': this.customer_id, 'staff_id': comp.id};
        let url = 'staff/customer/staff_id';
        this.restfulService.doPut(url, params).subscribe(commonResponse => this.handleResponseUpdateStaff(commonResponse, comp));
    }
    private handleResponseUpdateStatus(commonResponse : any){
            if(commonResponse.success){
            }else{
                alert(commonResponse.error);
            }
    }
    private handleResponseUpdateStaff(commonResponse : any, comp: any){
        if(commonResponse.success){
            this.data.customer.s_last_name = comp.last_name;
            this.data.customer.s_first_name = comp.first_name;
        }else{
            alert(commonResponse.error);
        }
    }
    private handleResponseDetail(commonResponse : any){
        if (commonResponse.success) {
            this.customer = commonResponse.data;
            if(this.customer){
				let birthday = moment(this.customer.birthday).toDate();
                this.model.birthday = { date: {year: birthday.getFullYear(), month: (birthday.getMonth() + 1), day: birthday.getDate() } };
				let created_at = moment(this.customer.created_at).toDate();
                this.model.created_at = { date: {year: created_at.getFullYear(), month: (created_at.getMonth() + 1), day: created_at.getDate() } };
			}
        } else {
            alert(commonResponse.error);
        }
    }
    private handleResponse(commonResponse : any){
        if (commonResponse.success) {
            this.data = commonResponse.data;
            this.model.status = this.data.customer.customer_status;
			//prepare data for GUI			
			let index=0, i=0;
			this.compatibilities = [];
			for(var key in this.data.compatibilities) {
				if(this.compatibilities[index] == null)
					this.compatibilities[index] = [];
				this.compatibilities[index].push(this.data.compatibilities[key]);
				i++;
				if(i%4==0)
					index++;
			}
        } else {
            alert(commonResponse.error);
        }
    }
    private handleResponseRemove(commonResponse : any){
        if(commonResponse.success){
            $("#remove-confirm").modal('hide');
            $("#remove-complete").modal('show');
        }
        else {
            alert(commonResponse.error);
        }
    }
    private handleResponseUpdate(commonResponse : any){
        if(commonResponse.success){
            $("#editConfirm").modal('hide');
            $("#editedsuccess").modal('show');
        }
        else {
            alert(commonResponse.error);
        }
    }
}
