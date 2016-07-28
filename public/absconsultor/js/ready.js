import simpleJSRoutingManager from './simple-js-routing-manager'

document.addEventListener('DOMContentLoaded', function () {

    simpleJSRoutingManager.settings({
        route_mode: true
    }).routes({
        '/': ['srmJuanController','run']
    }).run();
}, false);