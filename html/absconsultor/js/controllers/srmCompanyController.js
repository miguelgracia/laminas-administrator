import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmCompanyController() {

    this.indexAction = function() {

        $("#collaborators_carousel").owlCarousel({

            autoPlay: 3000, //Set AutoPlay to 3 seconds

            items : 4,
            itemsDesktop : [1199,3],
            itemsDesktopSmall : [979,3]

        });
    };

    this.collaboratorsAction = function() {

    };
}
simpleJSRoutingManager.srmController(srmCompanyController);

export default srmCompanyController;
