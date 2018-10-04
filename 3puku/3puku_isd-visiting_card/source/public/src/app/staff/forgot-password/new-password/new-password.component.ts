import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { RestfulService } from '../../../shared/services/restful.service';
declare var $: any;

@Component({
  selector: 'app-new-password',
  templateUrl: './new-password.component.html',
  styleUrls: ['./new-password.component.scss']
})
export class NewPasswordComponent implements OnInit {

    private confirm = false;
    private password: String;
    private c_password: String;
    private id: number;
    private token: String;

  constructor(private router: Router,
              private restfulService: RestfulService,
              private activeRoute: ActivatedRoute) { }

  ngOnInit() {
      $(function () {
          $('#forgot_password').validate({
              submitHandler: function(form){
                  return true;
              }
          });
      });
      this.activeRoute.params.subscribe(params => {
          [this.id, this.token] = params['token'].split('_');
      });
  }

    updatePassword(){
        if($('#forgot_password').valid()) {
        this.confirm = true;
        if(this.password == this.c_password){
            let data = {'id': this.id, 'token': this.token, 'password': this.password};
            let url = 'staff/reset_password';
            this.restfulService.doPost(url, data).subscribe(commonResponse => this.handleResponse(commonResponse));
            }
        }
    }
    private handleResponse(commonResponse: any){
        if(commonResponse.success){
            this.router.navigate(['staff/forgot-password/changed-password'])
        }else{
            alert(commonResponse.error);
        }
    }
}
