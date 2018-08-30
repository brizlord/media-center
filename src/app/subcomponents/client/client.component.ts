import {Component, OnInit} from '@angular/core';
import {DataService} from "../../services/data-service.service";
import {Router} from "@angular/router";

@Component({
    selector: 'app-client',
    templateUrl: './client.component.html',
    styleUrls: ['./client.component.css']
})
export class ClientComponent implements OnInit {
    foldersList: any[];

    constructor(private dataServ: DataService,
                private router: Router) {
    }

    ngOnInit() {
        if (this.dataServ.breadcrumbs.length == 0) {
            // this.dataServ.AllMoviesFolders(this.dataServ.selectedCategory);
        }

        this.dataServ.getAllMoviesFolders()
            .subscribe(res => {
                this.foldersList = res;
            });

        this.dataServ.getChildFolders()
            .subscribe(res => {
                if (res.length > 0 && !res[res.length - 1].hasOwnProperty('detailsAccess')) {
                    this.foldersList = res;
                }
                else {
                    if (res[res.length - 1].hasOwnProperty('detailsAccess') && res[res.length - 1].detailsAccess == true)
                        this.router.navigate(['/details']);
                }
            });
    }

    onClickItem(item) {
        this.dataServ.ChildFolders(item);
    }
}
