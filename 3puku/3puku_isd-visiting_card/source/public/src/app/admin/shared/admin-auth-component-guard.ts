import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { AdminService } from './services/admin.service';

@Injectable()
export class AdminAuthComponentGuard implements CanActivate {
    constructor(private router: Router, private adminService: AdminService) {}
 
    public canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        if (!this.adminService.isAuthenticated()) {
            this.router.navigate(["admin/login"]);
            return false;
        } else {
            return true;
        }
    }
}