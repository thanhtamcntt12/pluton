import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';

import { AppComponent } from './app.component';
import { MenuComponent } from './menu/menu.component';
import { AboutComponent } from './about/about.component';

import { RouterModule, Routes } from '@angular/router';
import { FooterComponent } from './footer/footer.component';
import { ServicesComponent } from './services/services.component';
import { PortfolioComponent } from './portfolio/portfolio.component';
import { ClientsComponent } from './clients/clients.component';
import { PriceComponent } from './price/price.component';
import { ContactComponent } from './contact/contact.component';
import { LoginComponent } from './login/login.component';

const routes: Routes = [
  { path: 'services', component: ServicesComponent },
  { path: 'portfolio', component: PortfolioComponent },
  { path: 'clients', component: ClientsComponent },
  { path: 'price', component: PriceComponent },
  { path: 'contact', component: ContactComponent },
  { path: 'about', component: AboutComponent },
];

@NgModule({
  declarations: [
    AppComponent,
    MenuComponent,
    AboutComponent,
    FooterComponent,
    FooterComponent,
    ServicesComponent,
    PortfolioComponent,
    ClientsComponent,
    PriceComponent,
    ContactComponent,
    LoginComponent
  ],
  imports:[ 
    BrowserModule, 
    FormsModule, 
    RouterModule.forRoot(routes) 
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
