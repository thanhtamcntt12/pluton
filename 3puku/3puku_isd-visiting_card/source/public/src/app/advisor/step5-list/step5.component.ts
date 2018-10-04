import { Component, OnInit } from '@angular/core';
import { RestfulService } from '../../shared/services/restful.service';
declare var $: any;

@Component({
  selector: 'app-step5',
  templateUrl: './step5.component.html',
  styleUrls: ['./step5.component.scss']
})
export class AdvisorStep5Component implements OnInit {

	private step5s:any;
	private type3diagnoses:any;
	private type12diagnoses: any;
	private type60diagnoses: any;
	private total : number;
    private number_per_page = 10;
    private pager = 1;
	private number_items = 0;
	private page_list = [];
	private page_from = 1;
	step5_id : number;
	model = {type_3_diagnosis_id: '',type_12_diagnosis_id: '',type_60_diagnosis_id: '',step5:''};
	
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
		this.getListType_12_diagnoses();
		this.getListType_60_diagnoses();
	}
	
	private search() {
        let data = {
            'page_limit': this.number_per_page,
            'page_number': this.pager,
            'type_3_diagnosis_id': this.model.type_3_diagnosis_id,
			'type_12_diagnosis_id': this.model.type_12_diagnosis_id,
			'type_60_diagnosis_id': this.model.type_60_diagnosis_id,
        };
        this.getList(data);
    }
	
	private getListType_3_diagnoses(){
		let url = 'advisor/type3';
        this.restfulService.doGet(url,null).subscribe(commonResponse => this.handleResponseType3Diagnoses(commonResponse));
	}
	
	private handleResponseType3Diagnoses(commonResponse:any) {
        if (commonResponse.success) {
            this.type3diagnoses = commonResponse.data;
        } else {
            alert(commonResponse.error);
        }
    }
	
	private getListType_12_diagnoses(){
		let url = 'advisor/type12';
        this.restfulService.doGet(url,null).subscribe(commonResponse => this.handleResponseType12Diagnoses(commonResponse));
	}
	
	private handleResponseType12Diagnoses(commonResponse:any) {
        if (commonResponse.success) {
            this.type12diagnoses = commonResponse.data;
        } else {
            alert(commonResponse.error);
        }
    }
	
	private getListType_60_diagnoses(){
		let url = 'advisor/type60';
        this.restfulService.doGet(url,null).subscribe(commonResponse => this.handleResponseType60Diagnoses(commonResponse));
	}
	
	private handleResponseType60Diagnoses(commonResponse:any) {
        if (commonResponse.success) {
            this.type60diagnoses = commonResponse.data;
        } else {
            alert(commonResponse.error);
        }
    }
	
	private getList(data:any) {
        let url = 'advisor/step5/search';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
    }
	
	private handleResponse(commonResponse:any) {
        if (commonResponse.success) {
            this.step5s = commonResponse.data.data;
            this.total = commonResponse.data.total_items;
            this.number_items =  this.step5s.length;
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
        this.model.type_12_diagnosis_id = '';
		 this.model.type_60_diagnosis_id = '';
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
	
	editStep(step5_id){
		this.step5_id = step5_id;
        let data = {
            'step5_id': step5_id,
        };
        let url = 'advisor/step5/detail';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponseDetail(commonResponse));
    
	}
	
	private handleResponseDetail(commonResponse:any) {
        if (commonResponse.success) {
			this.model.step5 = commonResponse.data[0];			
        } else {
            alert(commonResponse.error);
        }
    }
	
	edit(){
		let url = 'advisor/step5/edit';
        let data = {
                    'step5_id' : this.step5_id,
                    'type_3_advice': this.model.step5['type_3_advice'],
					'type_12_advice': this.model.step5['type_12_advice'],
					'type_60_advice': this.model.step5['type_60_advice'],
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
