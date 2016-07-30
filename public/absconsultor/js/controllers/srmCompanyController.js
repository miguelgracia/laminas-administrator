import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmCompanyController() {

    this.index = function() {

        $("#collaborators_carousel").owlCarousel({

            autoPlay: 3000, //Set AutoPlay to 3 seconds

            items : 4,
            itemsDesktop : [1199,3],
            itemsDesktopSmall : [979,3]

        });
    };
}
simpleJSRoutingManager.srmController(srmCompanyController);

export default srmCompanyController;
