import {Injectable} from '@angular/core';
import {Http, Response} from "@angular/http";
import 'rxjs/add/operator/map';
import {Observable} from "rxjs/Observable";
import {BuildUrlService} from "./build_url/build-url.service";
import {Subject} from "rxjs/Subject";
import 'rxjs/add/operator/catch';
import {saveAs} from 'file-saver/FileSaver';
import {Proxy} from "../interfaces/Proxy";

@Injectable()
export class DataService {
    subject: Subject<any> = new Subject<any>();
    subjectFolderList: Subject<any> = new Subject<any>();
    subjectAllFolderList: Subject<any> = new Subject<any>();
    breadcrumbs: any[] = [];
    childFolders: any[] = [];
    subjectDataImdb: Subject<any> = new Subject<any>();
    selectedCategory: string;
    subjectProxyConfig: Subject<any> = new Subject<any>();

    proxyConfig: Proxy;

    constructor(private http: Http,
                private build_url: BuildUrlService) {

        this.proxyConfig = {
            proxy: "",
            port: "",
            user: "",
            pass: ""
        }
    }

    getAllMoviesFolders(): Observable<any> {
        return this.subjectAllFolderList.asObservable();
    }

    AllMoviesFolders(selectedCategory) {
        this.http.post("./../../assets/php/GetAllMoviesFolder.php", {category: selectedCategory}, {headers: this.build_url.headers})
            .subscribe((res: Response) => this.subjectAllFolderList.next(res.json()));
    }

    getChildFolders(): Observable<any> {
        return this.subjectFolderList.asObservable();
    }

    ChildFolders(item) {
        this.breadcrumbs.push(item);
        this.subject.next(this.breadcrumbs);

        this.http.post("./../../assets/php/GetChildFolders.php", JSON.stringify(item), this.build_url.headers)
            .subscribe(res => {
                this.subjectFolderList.next(res.json());
                this.childFolders = res.json();
            });
    }

    GetSize(itemPath): Observable<any> {
        return this.http.post("./../../assets/php/GetSize.php", JSON.stringify(itemPath), this.build_url.headers)
            .map(res => res.json()
            );
    }

    clearBreadCrumbs() {
        this.breadcrumbs = [];
        this.subject.next([]);
    }

    getBreadCrumbs(): Observable<any> {
        return this.subject.asObservable();
    }

    BackBreadCrumbs(index) {
        for (let i = index + 1; i < this.breadcrumbs.length; i++) {
            this.breadcrumbs.splice(i, 1);
            i = i - 1;
        }

        this.subject.next(this.breadcrumbs);
    }

    getIMDBApi(title, route) {
        this.http.get("/assets/data/" + title + ".json")
            .map(res => res.json())
            .subscribe(
                (data) => {
                    this.subjectDataImdb.next(data);
                },
                (err) => {
                    this.http.get("http://omdbapi.com/?apikey=f1b7ca4e&t=" + title)
                        .subscribe(res => {
                            this.http.post("./../../assets/php/SaveImdbData.php", {
                                data: JSON.stringify(res.json()),
                                proxyConfig: JSON.stringify(this.proxyConfig)
                            }, this.build_url.headers)
                                .map(result => result.json())
                                .subscribe((data) => {
                                    this.subjectDataImdb.next(data);

                                    this.http.post("./../../assets/php/ChangeFolderName.php", {
                                            title: data.Title,
                                            path: route
                                        },
                                        this.build_url.headers
                                    )
                                        .subscribe(changedName => {
                                            console.log(changedName);
                                        });
                                });
                        });
                }
            )

    }

    getDataImdb(): Observable<any> {
        return this.subjectDataImdb.asObservable();
    }

    GetCategories(): Observable<any> {
        return this.http.get("./../../assets/php/GetCategories.php")
            .map(res => res.json());
    }


    RequestProxyConfig() {
        this.http.get("./../../assets/php/GetProxyConfig.php")
            .subscribe(res => this.subjectProxyConfig.next(res.json()));
    }

    setProxyConfig(config): Observable<any> {
        return this.http.post("./../../assets/php/SetProxyConfig.php", {config: config}, this.build_url.headers)
            .map(res => {
                res.json();

                this.subjectProxyConfig.next(config)
            });
    }

    getProxyConfig(): Observable<any> {
        return this.subjectProxyConfig.asObservable();
    }
}
