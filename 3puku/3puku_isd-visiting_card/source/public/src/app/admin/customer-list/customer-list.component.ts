import { Component, OnInit } from '@angular/core';
import {IMyDpOptions} from 'mydatepicker';
import { RestfulService } from '../../shared/services/restful.service';
import { AdminCustomerSearchService } from './searchService';
declare var $: any;

@Component({
  selector: 'app-customer-list',
  templateUrl: './customer-list.component.html',
  styleUrls: ['./customer-list.component.scss']
})
export class AdminCustomerListComponent implements OnInit {

	private myDatePickerOptions:IMyDpOptions = {
        // other options...
        dateFormat: 'yyyy/mm/dd',
    };
    private status=['','初回来店','追客中','申込','取止','他決','断り'];
    private customers:any;
    private total : number;
    private number_per_page = 10;
   // private pager = 1;
    private number_items = 0;
    private page_list = [];
    private page_from =  1;

   // private model = { name: '', fromDate: null, toDate: null};

    constructor(private restfulService:RestfulService,private searchService: AdminCustomerSearchService) {
    }

	ngOnInit() {
		let data = {
			'name': this.searchService.name,
			'page': this.searchService.pager,
            'number_per_page': this.number_per_page
        };
        this.getList(data);
	}
	initSearch(){
        this.searchService.pager = 1;
        this.page_from = 1;
		let fromDate_input = $("#fromDate input").val();
		if(fromDate_input){
			if(fromDate_input.includes("/"))
				this.searchService.fromDate = { date: {year: parseInt(fromDate_input.slice(0, 4)), month: parseInt(fromDate_input.slice(5,7)), day: parseInt(fromDate_input.slice(8)) } };
			else
				this.searchService.fromDate = { date: {year: parseInt(fromDate_input.slice(0,4)), month: parseInt(fromDate_input.slice(4,6)), day: parseInt(fromDate_input.slice(6)) } };
		}else{
			this.searchService.fromDate = null;
		}
		
		let toDate_input = $("#toDate input").val();
		if(toDate_input){
			if(fromDate_input.includes("/"))
				this.searchService.toDate = { date: {year: parseInt(toDate_input.slice(0, 4)), month: parseInt(toDate_input.slice(5,7)), day: parseInt(toDate_input.slice(8)) } };
			else
				this.searchService.toDate = { date: {year: parseInt(toDate_input.slice(0,4)), month: parseInt(toDate_input.slice(4,6)), day: parseInt(toDate_input.slice(6)) } };
		}else{
			this.searchService.toDate = null;
		}
		
        this.search();
    }
    current(pager){
        this.searchService.pager = pager;
        this.search();
    }
    prev(){
        if(this.page_from == this.searchService.pager)this.page_from = this.page_from - 1;
        this.searchService.pager = this.searchService.pager - 1;
        this.search();
    }
    next(){
        if(this.page_from + this.number_per_page - 1 == this.searchService.pager) this.page_from = this.page_from + 1;
        this.searchService.pager = this.searchService.pager + 1;
        this.search();
    }

    private handleResponse(commonResponse:any) {
        if (commonResponse.success) {
            this.customers = commonResponse.data.data;
            this.total = commonResponse.data.total;
            this.number_items =  this.customers.length;
            this.paginate();
        } else {
            alert(commonResponse.error);
        }
    }
    private  search() {
        let data = {
            'number_per_page': this.number_per_page,
            'page': this.searchService.pager,
            'name': this.searchService.name,
            'date_from': this.searchService.fromDate ?  this.searchService.fromDate.date.year + '-' + this.searchService.fromDate.date.month + '-' + this.searchService.fromDate.date.day : '' ,
            'date_to': this.searchService.toDate ?  this.searchService.toDate.date.year + '-' + this.searchService.toDate.date.month + '-' + this.searchService.toDate.date.day : '' ,
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
        let url = 'admin/customer/search';
        this.restfulService.doGet(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
    }

}
