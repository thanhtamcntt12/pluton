import { Component, OnInit } from '@angular/core';
import { RestfulService } from '../../shared/services/restful.service';
import { AdminCustomerSearchService } from '../customer-list/searchService';
declare var $: any;
@Component({
  selector: 'app-store-list',
  templateUrl: './store-list.component.html',
  styleUrls: ['./store-list.component.scss']
})
export class StoreListComponent implements OnInit {

    private stores:any;
	private info_store:any;
    total : number;
    number_per_page = 10;
    pager = 1;
    number_items = 0;
    page_list = [];
    store_id : number;
    private model = { store_name_kana: '',store_name: '',note: ''};
    private name = '';
    private page_from =  1;

	constructor(private restfulService:RestfulService, private searchService: AdminCustomerSearchService) { }

	ngOnInit() {
        $(function () {
            $('#form-validation').validate({
                rules:{
                    store_name_kana :{
                        katakana : true
                    }
                },
                submitHandler: function(form){
                    $("#store-edit").modal('hide');
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
            this.stores = commonResponse.data.data;
            this.total = commonResponse.data.total_items;
            this.number_items =  this.stores.length;
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
            if(i > 10) break;
            this.page_list.push( i);
        }
    }

    private getList(data:any) {
        let url = 'admin/store/search';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
    }

    detail(id_store) {
        this.store_id = id_store;
        let data = {
            'store_id': id_store,
        };
        let url = 'admin/store/detail';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponseDetail(commonResponse));
    }

    private handleResponseDetail(commonResponse:any) {
        if (commonResponse.success) {
            this.model.store_name_kana = commonResponse.data[0].store_name_kana;
            this.model.store_name = commonResponse.data[0].store_name;
            this.model.note = commonResponse.data[0].note;
        } else {
            alert(commonResponse.error);
        }
    }


    editStore(){


        let url = 'admin/store/edit';
        let data = {
                    'store_id' : this.store_id,
                    'store_name_kana': this.model.store_name_kana,
                    'store_name': this.model.store_name,
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

    deleteStore(){
        let url = 'admin/store/delete';
        let data = {
                    'store_id' : this.store_id
                    };
        this.restfulService.doPost(url,data).subscribe(commonResponse => this.handleResponseDelete(commonResponse));
    }

    private handleResponseDelete(commonResponse:any) {
        if (commonResponse.success) {
            $("#remove-confirm").modal('hide');
            $("#removeComplete").modal('show');
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
