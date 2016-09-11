import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmBlogController() {

    this.indexAction = function() {};

    this.categoryAction = function() {};

    this.detailAction = function() {

        $("#owl_carousel").owlCarousel({

            autoPlay: 5000, //Set AutoPlay to 3 seconds

            items : 1,
            itemsDesktop : [1199,1],
            itemsDesktopSmall : [979,1]

        });
    };
}
simpleJSRoutingManager.srmController(srmBlogController);

export default srmBlogController;
