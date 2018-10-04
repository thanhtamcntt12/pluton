import { Component, OnInit } from '@angular/core';
import { RestfulService } from '../../shared/services/restful.service';
import { AdminCustomerSearchService } from '../customer-list/searchService';
import * as moment from 'moment/moment';
declare var $: any;

@Component({
  selector: 'app-admin-list',
  templateUrl: './admin-list.component.html',
  styleUrls: ['./admin-list.component.scss']
})
export class AdminListComponent implements OnInit {

	private admins:any;
    private total : number;
    private number_per_page = 10;
    private pager = 1;
    private number_items = 0;
    private page_list = [];
    private page_from = 1;
	admin_id : number;
	private model = { last_name: '',first_name: '',last_name_kana: '',first_name_kana: '',email: '',note: ''};
    private name = '';

  	constructor(private restfulService:RestfulService, private searchService: AdminCustomerSearchService) { }

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
                    },
                    birthday:{
                        require: true
                    }
                },
                submitHandler: function(form){
                    $("#admin-edit").modal('hide');
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
		
		this.searchService.name = "";
		this.searchService.fromDate = "";
		this.searchService.toDate = "";
  	}

  	private handleResponse(commonResponse:any) {
        if (commonResponse.success) {
            this.admins = commonResponse.data.data;
            this.total = commonResponse.data.total_items;
            this.number_items =  this.admins.length;
            this.paginate();
        } else {
            alert(commonResponse.error);
        }
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
	
	clear() {
        this.name = '';
        let data = {
            'page_limit': this.number_per_page,
            'page_number': this.pager,
            'name': this.name,
        };
        this.getList(data);
    }

    private search() {
        let data = {
            'page_limit': this.number_per_page,
            'page_number': this.pager,
            'name': this.name,
        };
        this.getList(data);
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
        let url = 'admin/search';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
    }

    detail(id_admin) {
        this.admin_id = id_admin;
        let data = {
            'admin_id': id_admin,
        };
        let url = 'admin/detail';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponseDetail(commonResponse));
    }

    private handleResponseDetail(commonResponse:any) {
        if (commonResponse.success) {
            this.model.last_name = commonResponse.data[0].last_name;
            this.model.first_name = commonResponse.data[0].first_name;
            this.model.last_name_kana = commonResponse.data[0].last_name_kana;
            this.model.first_name_kana = commonResponse.data[0].first_name_kana;
            this.model.email = commonResponse.data[0].email;
            this.model.note = commonResponse.data[0].note;
        } else {
            alert(commonResponse.error);
        }
    }

    editAdmin(){
        let url = 'admin/edit';
        let data = {
                    'admin_id' : this.admin_id,
                    'last_name': this.model.last_name,
                    'first_name': this.model.first_name,
                    'last_name_kana': this.model.last_name_kana,
                    'first_name_kana': this.model.first_name_kana,
                    'email': this.model.email,
                    'note' : this.model.note
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

    deleteAdmin(){
        let url = 'admin/delete';
        let data = {
                    'admin_id' : this.admin_id
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

    edit_success(){
        this.search();
    }
    remove_success(){
        this.search();
    }

	cancelEditPopup() {
		var validator = $("#form-validation").validate();
		validator.resetForm();
		validator.reset();
	}

}
