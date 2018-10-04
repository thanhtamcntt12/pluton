import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { RestfulService } from '../../shared/services/restful.service';
declare var $: any;

@Component({
    selector: 'app-forgot-password',
    templateUrl: './forgot-password.component.html',
    styleUrls: ['./forgot-password.component.scss']
})
export class AdminForgotPasswordComponent implements OnInit {

    constructor(private router: Router,
                private restfulService: RestfulService,) { }

    private email;
    ngOnInit() {
        $(function () {
            $('#forgot_password').validate({
                submitHandler: function(form){
                    return true;
                }
            });
        });
    }

    sendEmail(){
        if($('#forgot_password').valid()){
        let data = {'email': this.email};
        let url = 'admin/send_email';
        this.restfulService.doPost(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
        }
    }

    private handleResponse(commonResponse: any){
        if(commonResponse.success){
            this.router.navigate(['admin/forgot-password/sendmail-confirm']);
        }else{
            alert(commonResponse.error);
        }
    }


}
