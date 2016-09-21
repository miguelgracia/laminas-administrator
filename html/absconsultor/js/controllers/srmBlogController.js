import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmBlogController() {

    this.indexAction = function() {};

    this.categoryAction = function() {};

    this.detailAction = function() {

        $("#owl_carousel").owlCarousel({
            autoHeight:true,
            singleItem: true,
            stopOnHover: true,
            items : 1
        });
    };
}
simpleJSRoutingManager.srmController(srmBlogController);

export default srmBlogController;
