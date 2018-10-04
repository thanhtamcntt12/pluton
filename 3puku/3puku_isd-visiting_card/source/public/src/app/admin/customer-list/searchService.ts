export class AdminCustomerSearchService {
	name: string;
	fromDate: any;
	toDate: any;
	pager: number; 
	
	constructor() {
		this.name = "";
		this.fromDate = "";
		this.toDate = "";
		this.pager = 1;
	}
}