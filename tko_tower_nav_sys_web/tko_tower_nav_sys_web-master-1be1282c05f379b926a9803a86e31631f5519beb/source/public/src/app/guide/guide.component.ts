import { Component, OnInit } from '@angular/core';
import {RestfulService} from '../services/restful.service';
declare var $: any;

@Component({
  selector: 'app-guide',
  templateUrl: './guide.component.html',
  styleUrls: ['./guide.component.scss']
})
export class GuideComponent implements OnInit {
	collection = [];
	private guides:any;
	private total : number;
	private number_per_page = 10;	
	private pager = 1;
	private delete_id : number;
	public types = [
		{id: 0, name: 'デフォルトモード'},
		{id: 1, name: '音声ガイドモード / 景観モード'}
	];
	model = {type: ''};
	
	constructor(private restfulService:RestfulService) { 
	}

	ngOnInit() {
		let data = {
                        'page_limit': this.number_per_page,
                        'page_number': this.pager
                    };
        this.getList(data);
	}
	
	pageChanged(event){
		this.pager = event;
		let data = {
                        'page_limit': this.number_per_page,
                        'page_number': this.pager
                    };
        this.getList(data);
	}
	
	private getList(data:any) {
        let url = 'guide/list';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
    }
	
	private handleResponse(commonResponse:any) {
		if(commonResponse==null) return;
        if (commonResponse.success) {
            this.guides = commonResponse.data.data;
            this.total = commonResponse.data.total_items;
        } else {
            alert(commonResponse.error);
        }
    }
	
	//----------delete--------------//
	delete_confirm(id){
		this.delete_id = id;
	}
	
	delete_guide(){
		  let url = 'guide/delete';
        let data = {
                    'guide_id' : this.delete_id
                    };
        this.restfulService.doPost(url,data).subscribe(commonResponse => this.handleResponseDelete(commonResponse));
	}
	
	private handleResponseDelete(commonResponse:any) {
		if(commonResponse==null) return;
        if (commonResponse.success) {
			$("#delete").modal('hide');
			$("#deletedsuccess").modal('show');
        } else {
            alert(commonResponse.error);
        }
    }
	delete_success(){
		$("#deletedsuccess").modal('hide');
		let data = {
				'page_limit': this.number_per_page,
				'page_number': this.pager
			};
		this.getList(data);
	}


}
