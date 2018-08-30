import {Component, Input, OnInit} from '@angular/core';
import {DataService} from "../../../services/data-service.service";
import {Item} from "../../../interfaces/item";
import {ActivatedRoute, Router} from '@angular/router';
import {DomSanitizer} from "@angular/platform-browser";

@Component({
    selector: 'app-details',
    templateUrl: './details.component.html',
    styleUrls: ['./details.component.css']
})
export class DetailsComponent implements OnInit {
    selectedItem: any[];
    folderSize = 0;
    picts: string[] = [];
    dataImdb: any = null;

    constructor(private dataServ: DataService,
                private sanitizerURL: DomSanitizer,
                private routerNav: Router,
                private route: ActivatedRoute) {
    }

    ngOnInit() {
        this.selectedItem = this.dataServ.childFolders;

        for (let i = 0; i < this.selectedItem.length; i++) {
            if (this.selectedItem[i].type == 'wallpaper') {
                this.picts.push('assets/img/movies/' + this.selectedItem[i].name);
            }
        }

        this.dataServ.GetSize(this.selectedItem[0].path).subscribe(res => {
            this.folderSize = res.size
        });

        this.dataServ.getIMDBApi(this.selectedItem[0].path.split('\\')[this.selectedItem[0].path.split('\\').length - 1], this.selectedItem[0].path);
        this.dataServ.getDataImdb()
            .subscribe(res => {
                    this.dataImdb = res;
                }
            );
    }

    onClickItem(item) {
        this.dataServ.ChildFolders(item);

        this.dataServ.getChildFolders()
            .subscribe(res => {
                this.selectedItem = res;
            });
    }


    sanitizerUrl(url: string) {
        // return this.sanitizerURL.bypassSecurityTrustUrl(url);

        window.open(url);
    }
}
