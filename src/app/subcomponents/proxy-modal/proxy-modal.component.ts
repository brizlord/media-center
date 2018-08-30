import {Component, OnInit} from '@angular/core';
import {DataService} from "../../services/data-service.service";
import {Router} from "@angular/router";

@Component({
    selector: 'app-proxy-modal',
    templateUrl: './proxy-modal.component.html',
    styleUrls: ['./proxy-modal.component.css']
})
export class ProxyModal implements OnInit {
    proxy: string;
    port: string;

    validProxyConfig: boolean;

    constructor(private dataServ: DataService,
                private router: Router) {
    }

    ngOnInit() {

        if (localStorage.getItem("mediaCenterProxyConfig")) {
            let config = localStorage.getItem("mediaCenterProxyConfig");

            this.dataServ.proxyConfig.proxy = config.split('=>')[0];
            this.dataServ.proxyConfig.port = config.split('=>')[1];
        }

        this.proxy = this.dataServ.proxyConfig.proxy;
        this.port = this.dataServ.proxyConfig.port;

        // this.dataServ.RequestProxyConfig();
        //
        // this.dataServ.getProxyConfig()
        //     .subscribe(res => {
        //         this.proxy = res.proxy;
        //         this.port = res.port;
        //     });
    }

    saveConfig() {
        if (localStorage.getItem("mediaCenterProxyConfig")) {
            localStorage.removeItem("mediaCenterProxyConfig");
        }

        localStorage.setItem("mediaCenterProxyConfig", this.proxy + "=>" + this.port);

        this.dataServ.proxyConfig.proxy = this.proxy;
        this.dataServ.proxyConfig.port = this.port;

        // this.dataServ.setProxyConfig({proxy: this.proxy, port: this.port})
        //     .subscribe(res => console.log(res.json()));
    }

}
