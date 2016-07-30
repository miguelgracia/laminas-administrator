import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmJobController() {

    this.detail = function() {

        $("#owl_carousel").owlCarousel({

            autoPlay: 5000, //Set AutoPlay to 3 seconds

            items : 1,
            itemsDesktop : [1199,1],
            itemsDesktopSmall : [979,1]

        });
    };
}
simpleJSRoutingManager.srmController(srmJobController);

export default srmJobController;
