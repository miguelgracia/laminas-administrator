import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmHomeController() {

    this.index = function() {

        var $nav = $('nav');

        $nav.css('top','-100%');

        var $homeBrandLogo = $('#home_brand_logo');
        var $homeNav = $(".video-wrapper .nav");

        $(window).scroll(function (e) {
            if($(this).scrollTop() < 50) {
                $homeBrandLogo.removeClass('header');
                $homeNav.removeClass('header');

                $nav.css('top','-' + ($nav.height()) + 'px');
            } else {
                $homeBrandLogo.addClass('header');
                $homeNav.addClass('header');
                $nav.css('top','0');
            }
        });
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