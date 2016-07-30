import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmHomeController() {

    this.index = function() {

        /*$('.bxslider').bxSlider({
            mode: 'fade',
            video: true,
            adaptiveHeight: true,
            useCSS: false,
            pager: false
        });*/
    };
}
simpleJSRoutingManager.srmController(srmHomeController);

export default srmHomeController;