import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Http, HttpModule, RequestOptions, XHRBackend  } from '@angular/http';
import { RouterModule, Routes } from '@angular/router';
import { CookieService } from 'angular2-cookie/services/cookies.service';

import { RestfulService } from './shared/services/restful.service';
import { HttpService } from './shared/services/http.service';

import { MyDatePickerModule } from 'mydatepicker';
import { MomentModule } from 'angular2-moment';

import { AppComponent } from './app.component';

import { AdminService } from './admin/shared/services/admin.service';
import { AdminAuthComponentGuard } from './admin/shared/admin-auth-component-guard';
import { AdminComponent } from './admin/admin.component';
import { AdminLoginComponent } from './admin/login/admin-login.component';
import { AdminAddComponent } from './admin/admin-add/admin-add.component';
import { AdminListComponent } from './admin/admin-list/admin-list.component';
import { AdminForgotPasswordComponent } from './admin/forgot-password/forgot-password.component';
import { AdminSendmailConfirmComponent } from './admin/forgot-password/sendmail-confirm/sendmail-confirm.component';
import { AdminNewPasswordComponent } from './admin/forgot-password/new-password/new-password.component';
import { AdminChangedPasswordComponent } from './admin/forgot-password/changed-password/changed-password.component';
import { StoreListComponent } from './admin/store-list/store-list.component';
import { StoreAddComponent } from './admin/store-add/store-add.component';
import { StaffListComponent } from './admin/staff-list/staff-list.component';
import { StaffAddComponent } from './admin/staff-add/staff-add.component';
import { AdminUsageComponent } from './admin/usage/admin-usage.component';
import { AdminCustomerListComponent } from './admin/customer-list/customer-list.component';
import { AdminCustomerViewComponent } from './admin/customer-view/customer-view.component';
import { AdminCustomerSearchService } from './admin/customer-list/searchService';

import { StaffService } from './staff/shared/services/staff.service';
import { StaffAuthComponentGuard } from './staff/shared/staff-auth-component-guard';
import { StaffComponent } from './staff/staff.component';
import { StaffLoginComponent } from './staff/login/staff-login.component';
import { CustomerListComponent } from './staff/customer-list/customer-list.component';
import { CustomerAddComponent } from './staff/customer-add/customer-add.component';
import { CustomerViewComponent } from './staff/customer-view/customer-view.component';
import { ForgotPasswordComponent } from './staff/forgot-password/forgot-password.component';
import { SendmailConfirmComponent } from './staff/forgot-password/sendmail-confirm/sendmail-confirm.component';
import { NewPasswordComponent } from './staff/forgot-password/new-password/new-password.component';
import { ChangedPasswordComponent } from './staff/forgot-password/changed-password/changed-password.component';
import { StaffUsageComponent } from './staff/usage/staff-usage.component';
import { StaffCustomerSearchService } from './staff/customer-list/searchService';

import { AdvisorService } from './advisor/shared/services/advisor.service';
import { AdvisorAuthComponentGuard } from './advisor/shared/advisor-auth-component-guard';
import { AdvisorComponent } from './advisor/advisor.component';
import { AdvisorLoginComponent } from './advisor/login/login.component';
import { AdvisorforgotPasswordComponent } from './advisor/forgot-password/forgot-password.component';
import { AdvisorSendmailConfirmComponent } from './advisor/forgot-password/sendmail-confirm/sendmail-confirm.component';
import { AdvisorNewPasswordComponent } from './advisor/forgot-password/new-password/new-password.component';
import { AdvisorChangedPasswordComponent } from './advisor/forgot-password/changed-password/changed-password.component';
import { AdvisorStep1Component } from './advisor/step1-list/step1.component';
import { AdvisorStep2Component } from './advisor/step2-list/step2.component';
import { AdvisorStep3Component } from './advisor/step3-list/step3.component';
import { AdvisorStep4Component } from './advisor/step4-list/step4.component';
import { AdvisorStep5Component } from './advisor/step5-list/step5.component';
import { AdvisorUsageComponent } from './advisor/usage/advisor-usage.component';

export function httpFactory(backend: XHRBackend, options: RequestOptions) {
    return new HttpService(backend, options);
}

const routes: Routes = [
	{ path: '', redirectTo: '/staff/login', pathMatch: 'full'},
	{ path: 'admin/login', component: AdminLoginComponent},
	{ path: 'admin/forgot-password', component: AdminForgotPasswordComponent},
	{ path: 'admin/forgot-password/sendmail-confirm', component: AdminSendmailConfirmComponent},
	{ path: 'admin/forgot-password/new-password/:token', component: AdminNewPasswordComponent},
	{ path: 'admin/forgot-password/changed-password', component: AdminChangedPasswordComponent},
	{ path: 'admin',  
		component: AdminComponent,
		children: [
		{ path: 'top',  component: AdminUsageComponent, canActivate: [AdminAuthComponentGuard]},
		{ path: 'admin',  component: AdminListComponent, canActivate: [AdminAuthComponentGuard]},
		{ path: 'admin/add',  component: AdminAddComponent, canActivate: [AdminAuthComponentGuard]},
		{ path: 'store',  component: StoreListComponent, canActivate: [AdminAuthComponentGuard]},
		{ path: 'store/add',  component: StoreAddComponent, canActivate: [AdminAuthComponentGuard]},
		{ path: 'staff',  component: StaffListComponent, canActivate: [AdminAuthComponentGuard]},
		{ path: 'staff/add',  component: StaffAddComponent, canActivate: [AdminAuthComponentGuard]},
		{ path: 'customer',  component: AdminCustomerListComponent, canActivate: [AdminAuthComponentGuard]},
		{ path: 'customer/view/:id',  component: AdminCustomerViewComponent, canActivate: [AdminAuthComponentGuard]}
		],
		canActivate: [AdminAuthComponentGuard]
	},
	{ path: 'staff/login',  component: StaffLoginComponent},
	{ path: 'staff/forgot-password',  component: ForgotPasswordComponent},
	{ path: 'staff/forgot-password/sendmail-confirm',  component: SendmailConfirmComponent},
	{ path: 'staff/forgot-password/new-password/:token',  component: NewPasswordComponent},
	{ path: 'staff/forgot-password/changed-password',  component: ChangedPasswordComponent},
	{ path: 'staff',  
		component: StaffComponent,
		children: [
			{ path: 'top',  component: StaffUsageComponent, canActivate: [StaffAuthComponentGuard]},
			{ path: 'customer',  component: CustomerListComponent, canActivate: [StaffAuthComponentGuard]},
			{ path: 'customer/add',  component: CustomerAddComponent, canActivate: [StaffAuthComponentGuard]},
			{ path: 'customer/view/:id',  component: CustomerViewComponent, canActivate: [StaffAuthComponentGuard]}
		],
		canActivate: [StaffAuthComponentGuard]
	},
	
	{ path: 'advisor/login',  component: AdvisorLoginComponent},
	{ path: 'advisor/forgot-password',  component: AdvisorforgotPasswordComponent},
	{ path: 'advisor/forgot-password/sendmail-confirm',  component: AdvisorSendmailConfirmComponent},
	{ path: 'advisor/forgot-password/new-password/:token',  component: AdvisorNewPasswordComponent},
	{ path: 'advisor/forgot-password/changed-password',  component: AdvisorChangedPasswordComponent},
	{ path: 'advisor',  
		component: AdvisorComponent,
		children: [
			{ path: 'top',  component: AdvisorUsageComponent, canActivate: [AdvisorAuthComponentGuard]},
			{ path: 'step1',  component: AdvisorStep1Component, canActivate: [AdvisorAuthComponentGuard]},
			{ path: 'step2',  component: AdvisorStep2Component, canActivate: [AdvisorAuthComponentGuard]},
			{ path: 'step3',  component: AdvisorStep3Component, canActivate: [AdvisorAuthComponentGuard]},
			{ path: 'step4',  component: AdvisorStep4Component, canActivate: [AdvisorAuthComponentGuard]},
			{ path: 'step5',  component: AdvisorStep5Component, canActivate: [AdvisorAuthComponentGuard]},
		],
		canActivate: [AdvisorAuthComponentGuard]
	},
];

@NgModule({
  declarations: [
    AppComponent,
    AdminComponent,
    AdminLoginComponent,
    AdminAddComponent,
    AdminListComponent,
    StaffComponent,
    StaffLoginComponent,
    CustomerListComponent,
    CustomerAddComponent,
    CustomerViewComponent,
    StoreListComponent,
    StoreAddComponent,
    StaffListComponent,
    StaffAddComponent,
    ForgotPasswordComponent,
    SendmailConfirmComponent,
    NewPasswordComponent,
    ChangedPasswordComponent,
	StaffUsageComponent,
    AdminForgotPasswordComponent,
    AdminSendmailConfirmComponent,
    AdminNewPasswordComponent,
    AdminChangedPasswordComponent,
	AdminUsageComponent,
    AdvisorComponent,
    AdvisorLoginComponent,
    AdvisorforgotPasswordComponent,
	AdvisorSendmailConfirmComponent,
	AdvisorNewPasswordComponent,
	AdvisorChangedPasswordComponent,
	AdvisorStep1Component,
	AdvisorStep2Component,
	AdvisorStep3Component,
	AdvisorStep4Component,
	AdvisorStep5Component,
	AdvisorUsageComponent,
	AdminCustomerListComponent,
	AdminCustomerViewComponent,
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    RouterModule.forRoot(routes),
	MyDatePickerModule ,
	MomentModule
  ],
  providers: [
	RestfulService,
      { provide: Http,
          useFactory: httpFactory,/*using HttpService */
          deps: [XHRBackend, RequestOptions]
      },
	CookieService,
	AdminAuthComponentGuard,
	AdminService,
	StaffAuthComponentGuard,
	StaffService,
	AdvisorAuthComponentGuard,
	AdvisorService,
	StaffCustomerSearchService,
	AdminCustomerSearchService
	],
  bootstrap: [AppComponent]
})
export class AppModule { }
