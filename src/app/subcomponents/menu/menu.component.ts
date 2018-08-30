import {Component, OnInit} from '@angular/core';
import {DataService} from "../../services/data-service.service";
import {Router} from "@angular/router";

@Component({
    selector: 'app-menu',
    templateUrl: './menu.component.html',
    styleUrls: ['./menu.component.css']
})
export class MenuComponent implements OnInit {
    categories: any[];
    selectedCategory: string = '';

    constructor(private dataServ: DataService,
                private router: Router) {
    }

    ngOnInit() {
        this.dataServ.GetCategories()
            .subscribe(res => {
                this.categories = res;
            });

        if (this.router.url == '/list') {
            this.selectedCategory = this.dataServ.selectedCategory;
        }
    }

    selectCategory(category) {
        this.dataServ.clearBreadCrumbs();

        this.selectedCategory = category;
        this.dataServ.selectedCategory = this.selectedCategory;

        this.dataServ.AllMoviesFolders(this.dataServ.selectedCategory);
    }

}
