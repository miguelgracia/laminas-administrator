import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmMyClass() {
    this.beforeController = function() {
        console.log('before controller');
    };
}
simpleJSRoutingManager.jsClass(srmMyClass);

export default srmMyClass