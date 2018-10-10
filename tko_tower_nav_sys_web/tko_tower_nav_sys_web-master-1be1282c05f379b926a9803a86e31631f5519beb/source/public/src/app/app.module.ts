import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Http, HttpModule, RequestOptions, XHRBackend } from '@angular/http';
import { RouterModule, Routes } from '@angular/router';
import {NgxPaginationModule} from 'ngx-pagination'; 
//service
import { RestfulService } from './services/restful.service';
import { HttpService } from './services/http.service';

//component
import { AppComponent } from './app.component';
import { BeaconComponent } from './beacon/beacon.component';
import { GuideComponent } from './guide/guide.component';
import { GuideAddComponent } from './guide-add/guide-add.component';
import { GuideEditComponent } from './guide-edit/guide-edit.component';

export function httpFactory(backend: XHRBackend, options: RequestOptions) {
    return new HttpService(backend, options);
}

const routes = [
    {path:'', redirectTo: '/beacon', pathMatch: 'full'},
    {path:'beacon', component: BeaconComponent},
	{path:'guide', component: GuideComponent},
	{path:'guide/add', component: GuideAddComponent},
	{path:'guide/edit/:id', component: GuideEditComponent}
];

@NgModule({
  declarations: [
    AppComponent,
    BeaconComponent,
    GuideComponent,
    GuideAddComponent,
    GuideEditComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
	NgxPaginationModule,
	RouterModule.forRoot(routes),
  ],
  providers: [RestfulService,
      { provide: Http,
          useFactory: httpFactory,/*using HttpService */
          deps: [XHRBackend, RequestOptions]
      }],
  bootstrap: [AppComponent]
})
export class AppModule { }
