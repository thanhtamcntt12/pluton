import { Component, OnInit } from '@angular/core';
import {IMyDpOptions} from 'mydatepicker';
import { RestfulService } from '../../shared/services/restful.service';
import { AdminCustomerSearchService } from '../customer-list/searchService';
import * as moment from 'moment/moment';
declare var $: any;

@Component({
  selector: 'app-staff-list',
  templateUrl: './staff-list.component.html',
  styleUrls: ['./staff-list.component.scss']
})
export class StaffListComponent implements OnInit {
	private myDatePickerOptions:IMyDpOptions = {
        // other options...
        dateFormat: 'yyyy/mm/dd',
    };
	
	private staffs:any;
	private stores:any;
    private total : number;
    private number_per_page = 10;
    private pager = 1;
    private number_items = 0;
    private page_list = [];
    private page_from = 1;
	staff_id : number;
	model = {birthday: null,store_id: '',store_name: '',name: '',staff:''};
	
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
					$("#staff-edit").modal('hide');
                    $("#editConfirm").modal('show');
                    return false;
                }
            });
        });
		let data = {
                        'page_limit': this.number_per_page,
                        'page_number': this.pager
                    };
        this.getList(data);
		this.getListStore();
		
		this.searchService.name = "";
		this.searchService.fromDate = "";
		this.searchService.toDate = "";
	}
	
	private handleResponse(commonResponse:any) {
        if (commonResponse.success) {
            this.staffs = commonResponse.data.data;
            this.total = commonResponse.data.total_items;
            this.number_items =  this.staffs.length;
            this.paginate();
        } else {
            alert(commonResponse.error);
        }
    }
	
	private paginate(){
        let number_page = Math.ceil(this.total / this.number_per_page);
        this.page_list = [];
        for(let i = this.page_from ; i <= number_page; i++ ){
            if(i >= 10) break;
            this.page_list.push( i);
        }
    }

	private getList(data:any) {
        let url = 'admin/staff/search';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
    }
	
	private handleResponseStore(commonResponse:any) {
        if (commonResponse.success) {
            this.stores = commonResponse.data.data;
        } else {
            alert(commonResponse.error);
        }
    }
	
	private getListStore(){
		let url = 'admin/store/list';
        this.restfulService.doGet(url,null).subscribe(commonResponse => this.handleResponseStore(commonResponse));
	}
	
    private search() {
        let data = {
            'page_limit': this.number_per_page,
            'page_number': this.pager,
            'name': this.model.name,
			'store_id': this.model.store_id,
        };
        this.getList(data);
    }
    initSearch(){
        this.pager = 1;
        this.page_from = 1;
        this.search();
    }
    current(pager){
        this.pager = pager;
        this.search();
    }
    prev(){
        if(this.page_from == this.pager)this.page_from = this.page_from - 1;
        this.pager = this.pager - 1;
        this.search();
    }
    next(){
        if(this.page_from + this.number_per_page - 1 == this.pager) this.page_from = this.page_from + 1;
        this.pager = this.pager + 1;
        this.search();
    }
	detail(staff_id){
		this.staff_id = staff_id;
        let data = {
            'staff_id': staff_id,
        };
        let url = 'admin/staff/detail';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponseDetail(commonResponse));
    }
	
	private handleResponseDetail(commonResponse:any) {
        if (commonResponse.success) {
			this.model.staff = commonResponse.data[0];			
        } else {
            alert(commonResponse.error);
        }
    }
	
	page_edit(){
		let birthday = moment(this.model.staff['birthday']).toDate();
        this.model.birthday = { date: {year: birthday.getFullYear(), month: (birthday.getMonth() + 1), day: birthday.getDate() } };
	}
	
	confirm() {
		if(!$("#form-validation").valid()) return;
		
		this.model.store_name = this.findStoreName(this.model.staff['store_id']);
		let birthday_input = $("#birthday input").val();
		if(birthday_input.includes("/"))
			this.model.birthday = { date: {year: parseInt(birthday_input.slice(0, 4)), month: parseInt(birthday_input.slice(5,7)), day: parseInt(birthday_input.slice(8)) } };
		else
			this.model.birthday = { date: {year: parseInt(birthday_input.slice(0,4)), month: parseInt(birthday_input.slice(4,6)), day: parseInt(birthday_input.slice(6)) } };
	}
	
	private findStoreName(store_id){
		for (var i=0; i < this.stores.length; i++) {
			if (this.stores[i].id == store_id) {
				return this.stores[i].store_name;
			}
		}
	}
	
	editStaff(){
        let url = 'admin/staff/edit';
        let data = {
                    'staff_id' : this.staff_id,
                    'last_name': this.model.staff['last_name'],
					'first_name': this.model.staff['first_name'],
                    'last_name_kana': this.model.staff['last_name_kana'],
					'first_name_kana': this.model.staff['first_name_kana'],
					'birthday': this.model.birthday.date['year']+'-'+this.model.birthday.date['month']+'-'+this.model.birthday.date['day'],
					'email': this.model.staff['email'],
					'store_id': this.model.staff['store_id'],
                    'note' : this.model.staff['note']
                    };
        this.restfulService.doPost(url,data).subscribe(commonResponse => this.handleResponseEdit(commonResponse));
	}
	
	private handleResponseEdit(commonResponse:any) {
        if (commonResponse.success) {
            $("#editConfirm").modal('hide');
            $("#editedsuccess").modal('show');
        } else {
            $("#editConfirm").modal('hide');
            alert(commonResponse.error);
        }
    }
	
	deleteStaff(){
        let url = 'admin/staff/delete';
        let data = {
                    'staff_id' : this.staff_id
                };
        this.restfulService.doPost(url,data).subscribe(commonResponse => this.handleResponseDelete(commonResponse));
    }
	
	private handleResponseDelete(commonResponse:any) {
        if (commonResponse.success) {
            $("#remove-confirm").modal('hide');
            $("#remove-complete").modal('show');
        } else {
            $("#remove-confirm").modal('hide');
            alert(commonResponse.error);
        }
    }
	
	remove_success(){
        location.reload();
    }
	
	clear() {
		this.model.name = '';
        this.model.store_id = '';
        this.search();
    }

	cancelEditPopup() {
		var validator = $("#form-validation").validate();
		validator.resetForm();
		validator.reset();
	}
}
