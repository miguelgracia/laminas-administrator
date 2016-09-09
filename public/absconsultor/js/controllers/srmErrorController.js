import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmErrorController() {

    this.indexAction = function() {};

}
simpleJSRoutingManager.srmController(srmErrorController);

export default srmErrorController;
