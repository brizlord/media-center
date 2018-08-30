import { Injectable } from '@angular/core';
import { URLSearchParams } from '@angular/http';
import { Headers } from '@angular/http';


@Injectable()
export class BuildUrlService {
    headers:Headers;
    uploadHeaders:Headers;

    constructor() {
        this.headers = new Headers();
        this.headers.append("Content-Type", "application/x-www-form-urlencoded");

        this.uploadHeaders=new Headers();
        // this.uploadHeaders.append('enctype', 'multipart/form-data');
        this.uploadHeaders.append('Accept', 'application/json');
    }

    _buildParams(params:any) {
        let urlSearchParams = new URLSearchParams();

        for (let key in params) {
            if (params.hasOwnProperty(key)) {
                urlSearchParams.append(key, params[key]);
            }
        }

        return urlSearchParams.toString();
    }

    _buildParamsWFunction(funct:string, params:any) {
        let urlSearchParams = new URLSearchParams();

        urlSearchParams.append('f', funct);

        for (let key in params) {
            if (params.hasOwnProperty(key)) {
                urlSearchParams.append(key, params[key]);
            }
        }

        return urlSearchParams.toString();
    }
}
