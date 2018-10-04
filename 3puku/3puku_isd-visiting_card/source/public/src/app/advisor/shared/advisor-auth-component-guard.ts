import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { AdvisorService } from './services/advisor.service';

@Injectable()
export class AdvisorAuthComponentGuard implements CanActivate {
    constructor(private router: Router, private advisorService: AdvisorService) {}
 
    public canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        if (!this.advisorService.isAuthenticated()) {
            this.router.navigate(["advisor/login"]);
            return false;
        } else {
            return true;
        }
    }
}