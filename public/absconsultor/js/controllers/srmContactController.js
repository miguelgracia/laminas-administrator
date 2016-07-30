import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmContactController() {

    this.index = function() {

        console.log('Contact controller');
    };
}
simpleJSRoutingManager.srmController(srmContactController);

export default srmContactController;