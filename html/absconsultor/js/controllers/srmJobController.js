import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmJobController() {

    this.indexAction = function() {};

    this.categoryAction = function() {};

    this.detailAction = function() {

        $("#owl_carousel").owlCarousel({
            autoPlay: 5000, //Set AutoPlay to 3 seconds
            autoHeight:true,
            singleItem: true,
            items : 1
        });
    };
}
simpleJSRoutingManager.srmController(srmJobController);

export default srmJobController;
