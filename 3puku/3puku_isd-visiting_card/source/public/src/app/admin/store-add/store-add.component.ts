import { Component, OnInit } from '@angular/core';
import { RestfulService } from '../../shared/services/restful.service';
import { AdminCustomerSearchService } from '../customer-list/searchService';
declare var $: any;
@Component({
  selector: 'app-store-add',
  templateUrl: './store-add.component.html',
  styleUrls: ['./store-add.component.scss']
})
export class StoreAddComponent implements OnInit {

	model = { store_name_kana: '', store_name: '',note: ''};
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
                    $("#addConfirm").modal('show');
                    return false;
                }
            });
        });
		this.searchService.name = "";
		this.searchService.fromDate = "";
		this.searchService.toDate = "";
	}

	addStore() {
		let url = 'admin/store/add';
	    let data = {
                    'store_name_kana': this.model.store_name_kana,
                    'store_name': this.model.store_name,
                    'note' : this.model.note
                    };
        this.restfulService.doPost(url,data).subscribe(commonResponse => this.handleResponse(commonResponse));
	}

	private handleResponse(commonResponse:any) {
        if (commonResponse.success) {
        	$("#addConfirm").modal('hide');
        	$("#addedsuccess").modal('show');
        } else {
        	$("#addConfirm").modal('hide');
            alert(commonResponse.error);
        }
    }

}
