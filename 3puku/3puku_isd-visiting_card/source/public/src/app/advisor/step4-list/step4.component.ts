import { Component, OnInit } from '@angular/core';
import { RestfulService } from '../../shared/services/restful.service';
declare var $: any;

@Component({
  selector: 'app-step4',
  templateUrl: './step4.component.html',
  styleUrls: ['./step4.component.scss']
})

export class AdvisorStep4Component implements OnInit {
	
	private step4s:any;
	private type3diagnosis:any;
	private type12intent: any
	private total : number;
    private number_per_page = 10;
    private pager = 1;
	private number_items = 0;
	private page_list = [];
	private page_from = 1;
	step4_id : number;
	model = {type_3_diagnosis_id: '',type_12_intent_diagnosis_id: '',step4:''};
	
	constructor(private restfulService:RestfulService) { }

	ngOnInit() {
		$(function () {
            $('#form-validation').validate({
                submitHandler: function(form){
					$("#edit").modal('hide');
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
		this.getListType_3_diagnoses();
		this.getListType_12_intent_diagnoses();
	}
	
	private search() {
        let data = {
            'page_limit': this.number_per_page,
            'page_number': this.pager,
            'type_3_diagnosis_id': this.model.type_3_diagnosis_id,
			'type_12_intent_diagnosis_id': this.model.type_12_intent_diagnosis_id,
        };
        this.getList(data);
    }
	
	private getListType_3_diagnoses(){
		let url = 'advisor/type3';
        this.restfulService.doGet(url,null).subscribe(commonResponse => this.handleResponseType3Diagnoses(commonResponse));
	}
	
	private handleResponseType3Diagnoses(commonResponse:any) {
        if (commonResponse.success) {
            this.type3diagnosis = commonResponse.data;
        } else {
            alert(commonResponse.error);
        }
    }
	
	private getListType_12_intent_diagnoses(){
		let url = 'advisor/type12intent';
        this.restfulService.doGet(url,null).subscribe(commonResponse => this.handleResponseType12Intent(commonResponse));
	}
	private handleResponseType12Intent(commonResponse:any) {
        if (commonResponse.success) {
            this.type12intent = commonResponse.data;
        } else {
            alert(commonResponse.error);
        }
    }
	
	
	private getList(data:any) {
        let url = 'advisor/step4/search';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
    }
	
	private handleResponse(commonResponse:any) {
        if (commonResponse.success) {
            this.step4s = commonResponse.data.data;
            this.total = commonResponse.data.total_items;
            this.number_items =  this.step4s.length;
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
	
	clear() {
		this.model.type_3_diagnosis_id = '';
        this.model.type_12_intent_diagnosis_id = '';
        this.search();
    }
	
	private paginate(){
        let number_page = Math.ceil(this.total / this.number_per_page);
        this.page_list = [];
        for(let i = this.page_from ; i <= number_page; i++ ){
            if(i >= 10) break;
            this.page_list.push( i);
        }
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
	
	editStep(step4_id){
		this.step4_id = step4_id;
        let data = {
            'step4_id': step4_id,
        };
        let url = 'advisor/step4/detail';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponseDetail(commonResponse));
    
	}
	
	private handleResponseDetail(commonResponse:any) {
        if (commonResponse.success) {
			this.model.step4 = commonResponse.data[0];			
        } else {
            alert(commonResponse.error);
        }
    }
	
	edit(){
		let url = 'advisor/step4/edit';
        let data = {
                    'step4_id' : this.step4_id,
                    'type_12_advice': this.model.step4['type_12_advice'],
					'type_3_advice': this.model.step4['type_3_advice'],
                    };
        this.restfulService.doPost(url,data).subscribe(commonResponse => this.handleResponseEdit(commonResponse));
	}
	
	private handleResponseEdit(commonResponse:any) {
        if (commonResponse.success) {
            $("#editConfirm").modal('hide');
            $("#editedSuccess").modal('show');
        } else {
            $("#editConfirm").modal('hide');
            alert(commonResponse.error);
        }
    }
	
	cancelEditPopup() {
		var validator = $("#form-validation").validate();
		validator.resetForm();
		validator.reset();
	}

}
