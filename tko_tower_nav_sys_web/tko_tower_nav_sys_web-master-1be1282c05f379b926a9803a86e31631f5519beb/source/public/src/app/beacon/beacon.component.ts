import { Component, OnInit} from '@angular/core';
import {RestfulService} from '../services/restful.service';
declare var $: any;

@Component({
  selector: 'app-beacon',
  templateUrl: './beacon.component.html',
  styleUrls: ['./beacon.component.scss']
})
export class BeaconComponent implements OnInit {
	private beacons:any;
	private total : number;
	private number_per_page = 10;
	private pager = 1;
	private delete_id : number;
	public types = [
		{id: 0, name: 'デフォルトモード'},
		{id: 1, name: '音声ガイドモード / 景観モード'},
		{id: 2, name: '押しビーコン'},
		{id: 3, name: '長押しビーコン'}
	];
	model = {type: 0,name:'',uuid1:'',uuid2:'',uuid3:'',uuid4:'',uuid5:'',major:'',minor:'',
			 edit_id: '',edit_type: 0,edit_name:'',edit_uuid1:'',edit_uuid2:'',edit_uuid3:'',edit_uuid4:'',edit_uuid5:'',edit_major:'',edit_minor:''};
	
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
        let url = 'beacon/list';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
    }
	
	private handleResponse(commonResponse:any) {
		if(commonResponse==null) return;
        if (commonResponse.success) {
            this.beacons = commonResponse.data.data;
            this.total = commonResponse.data.total_items;
			for(var i = 0; i < this.beacons.length; i++ )
				for (var j = 0; j < this.types.length; j++) {
					if (this.types[j].id == this.beacons[i]['type']) {
						this.beacons[i]['type_name'] =  this.types[j]['name'];
						break;
					}
				}
        } else {
            alert(commonResponse.error);
        }
    }
	
	//----------add--------------//
	add_beacon() {
		this.model.type = 0;
		this.model.name = '';
		this.model.uuid1 = '';
		this.model.uuid2 = '';
		this.model.uuid3 = '';
		this.model.uuid4 = '';
		this.model.uuid5 = '';
	}
	add() {
		let url = 'beacon/add';
	    let data = {
                    'name': this.model.name,
                    'uuid': this.model.uuid1+"-"+this.model.uuid2+"-"+this.model.uuid3+"-"+this.model.uuid4+"-"+this.model.uuid5,
					'major': this.model.major,
					'minor': this.model.minor,
                    'type' : this.model.type
                    };
        this.restfulService.doPost(url,data).subscribe(commonResponse => this.handleResponseAdd(commonResponse));
	}

	private handleResponseAdd(commonResponse:any) {
		if(commonResponse==null) return;
        if (commonResponse.success) {
			$("#add").modal('hide');
			$("#addedsuccess").modal('show');
        } else {
            alert(commonResponse.error);
        }
    }
	
	add_success(){
		$("#addedsuccess").modal('hide');
		let data = {
				'page_limit': this.number_per_page,
				'page_number': this.pager
			};
		this.getList(data);
	}
	
	onChangeAddType(){
		if(this.model.type!=3){
			this.model.major = String(0xff & parseInt(this.model.major));
		}
		if(this.model.type==3){
			this.model.major = String((0x10 <<8) | parseInt(this.model.major));
		}
	}
	
	//----------edit--------------//
	edit_beacon(index){
		let uuid = this.beacons[index]['uuid'].split("-");
		this.model.edit_id = this.beacons[index]['id'];
		this.model.edit_name = this.beacons[index]['name'];
		this.model.edit_uuid1 = uuid[0];
		this.model.edit_uuid2 = uuid[1];
		this.model.edit_uuid3 = uuid[2];
		this.model.edit_uuid4 = uuid[3];
		this.model.edit_uuid5 = uuid[4];
		this.model.edit_major = this.beacons[index]['major'];
		this.model.edit_minor = this.beacons[index]['minor'];
		this.model.edit_type = this.beacons[index]['type'];
	}
	
	edit(id) {
		let url = 'beacon/edit';
	    let data = {
					'id':id, 
                    'name': this.model.edit_name,
                    'uuid': this.model.edit_uuid1+"-"+this.model.edit_uuid2+"-"+this.model.edit_uuid3+"-"+this.model.edit_uuid4+"-"+this.model.edit_uuid5,
                    'major': this.model.edit_major,
					'minor': this.model.edit_minor,
					'type' : this.model.edit_type
                    };
        this.restfulService.doPost(url,data).subscribe(commonResponse => this.handleResponseEdit(commonResponse));
	}

	private handleResponseEdit(commonResponse:any) {
		if(commonResponse==null) return;
        if (commonResponse.success) {
			$("#edit").modal('hide');
			$("#editedsuccess").modal('show');
        } else {
            alert(commonResponse.error);
        }
    }
	
	edit_success(){
		$("#editedsuccess").modal('hide');
		let data = {
				'page_limit': this.number_per_page,
				'page_number': this.pager
			};
		this.getList(data);
	}
	
	onChangeEditType(){
		if(this.model.edit_type!=3){
			this.model.edit_major = String(0xff & parseInt(this.model.edit_major));
		}
		if(this.model.edit_type==3){
			this.model.edit_major = String((0x10 <<8) | parseInt(this.model.edit_major));
		}
	}
	
	//----------delete--------------//
	delete_beacon(id){
		this.delete_id = id;
	}
	
	delete_beacon2(){
		  let url = 'beacon/delete';
        let data = {
                    'id' : this.delete_id
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
