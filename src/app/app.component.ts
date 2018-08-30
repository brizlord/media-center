import {Component, OnInit} from '@angular/core';
import {DataService} from "./services/data-service.service";
import {Router} from "@angular/router";
import {toast} from 'angular2-materialize';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
    breadCrumbs: any[];

    constructor(private dataServ: DataService,
                private router: Router) {
    }

    ngOnInit() {
        if (!localStorage.getItem("mediaCenterProxyConfig")) {
            toast("You can setting a proxy if you need it! :) <button class=\"btn-flat toast-action modal-trigger\" href=\"#proxyModal\">Config</button>", 4000);
        }

        this.dataServ.getBreadCrumbs()
            .subscribe(res => {
                this.breadCrumbs = res;
            });
    }

    bcLocation(bcItem, index) {
        if (this.router.url != "/list") {
            this.router.navigate(['/list']);
        }

        this.dataServ.ChildFolders(bcItem);

        this.dataServ.BackBreadCrumbs(index);
    }

    onBack() {
        if (this.breadCrumbs.length > 1) {
            if (this.router.url != '/list') {
                this.router.navigate(['/list']);
            }

            this.dataServ.BackBreadCrumbs(this.breadCrumbs.length - 2);

            this.dataServ.ChildFolders(this.breadCrumbs[this.breadCrumbs.length - 1]);

            this.dataServ.BackBreadCrumbs(this.breadCrumbs.length - 2);
        }
        else {
            this.dataServ.BackBreadCrumbs(-1);

            this.dataServ.AllMoviesFolders(this.dataServ.selectedCategory);

            if (this.router.url != '/list') {
                this.router.navigate(['/list']);
            }
        }
    }
}
