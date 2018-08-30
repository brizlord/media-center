import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {FormsModule} from '@angular/forms';
import {RouterModule, Routes} from '@angular/router';
import {HttpModule} from '@angular/http';

import {MaterializeModule} from 'angular2-materialize';

import {AppComponent} from './app.component';
import {ClientComponent} from './subcomponents/client/client.component';
import {AdminComponent} from './subcomponents/admin/admin.component';
import {DetailsComponent} from './subcomponents/client/details/details.component';
import {ProxyModal} from "./subcomponents/proxy-modal/proxy-modal.component";
import {MenuComponent} from "./subcomponents/menu/menu.component";

import {DataService} from './services/data-service.service';
import {BuildUrlService} from "./services/build_url/build-url.service";

const myRoutes: Routes = [
    {
        path: 'list',
        component: ClientComponent
    },
    {
        path: 'details',
        component: DetailsComponent
    },
    {
        path: '',
        redirectTo: '',
        pathMatch: 'full'
    }
];

@NgModule({
    declarations: [
        AppComponent,
        ClientComponent,
        AdminComponent,
        DetailsComponent,
        MenuComponent,
        ProxyModal
    ],
    imports: [
        BrowserModule,
        MaterializeModule,
        RouterModule.forRoot(myRoutes),
        HttpModule,
        FormsModule
    ],
    providers: [DataService, BuildUrlService],
    bootstrap: [AppComponent]
})
export class AppModule {
}
