import simpleJSRoutingManager from './simple-js-routing-manager'

document.addEventListener('DOMContentLoaded', function () {

    simpleJSRoutingManager.settings({
        route_mode: true
    }).routes({
        '/contacto':                ['srmContactController','index'],
        '/trabajos/{:any}/{:any}':  ['srmJobController','detail'],
        '/empresa':                 ['srmCompanyController','index'],
        '/':                        ['srmHomeController','index']
    }).run();
}, false);