import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmHomeController() {

    this.run = function() {
        console.log(this);
        console.log('Juan controller');
    };
}
simpleJSRoutingManager.srmController(srmHomeController);

export default srmHomeController;