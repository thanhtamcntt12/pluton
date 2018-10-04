import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { StaffService } from './services/staff.service';

@Injectable()
export class StaffAuthComponentGuard implements CanActivate {
    constructor(private router: Router, private staffService: StaffService) {}
 
    public canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        if (!this.staffService.isAuthenticated()) {
            this.router.navigate(["staff/login"]);
            return false;
        } else {
            return true;
        }
    }
}