import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmHomeController() {

    this.indexAction = function() {

        var $slides = $('.bxslider'), video;

        var bxSlider = $slides.bxSlider({
            mode: 'fade',
            adaptiveHeight: true,
            pager: false,
            preloadImages: 'all',
            onSlideAfter: function ($slideElement, oldIndex, newIndex) {
                var $videoWrapper = $slideElement.find('.video-wrapper'), $play, $pause;

                if($videoWrapper) {

                    $play = $videoWrapper.find('.fa-play');
                    $pause = $videoWrapper.find('.fa-pause');

                    if(video) {
                        video.pause();
                        $slides.children().eq(oldIndex).find('.fa-play').removeClass('hide');
                        $slides.children().eq(oldIndex).find('.fa-pause').addClass('hide');
                    }

                    video = $videoWrapper.find('video')[0];

                    var videoControlEvents = function(controlType) {

                        return function(e) {

                            switch (controlType) {
                                case 'play':
                                    $play.addClass('hide');
                                    $pause.removeClass('hide');
                                    break;
                                case 'pause':
                                    $play.removeClass('hide');
                                    $pause.addClass('hide');
                                    break;
                            }

                            video[controlType]();
                        };
                    };

                    $play.click(videoControlEvents('play'));
                    $pause.click(videoControlEvents('pause'));
                }
            }
        });

        var $nav = $('nav');

        var $homeBrandLogo = $('#home_brand_logo'),
            $homeNav = $(".home-navbar.nav"),
            $chevronDown = $(".fa.fa-chevron-down");

        var centerLogo = function() {
            var mLeft = (-($homeBrandLogo.width() / 2).toString() + 'px');
            var mTop = (-(($homeBrandLogo.height() / 2) + 50).toString() + 'px');
            $homeBrandLogo.css('margin-left',mLeft);
            $homeBrandLogo.css('margin-top',mTop);
        };

        $(window).scroll(function (e) {
            if($(this).scrollTop() < 50) {
                $homeBrandLogo.removeClass('header');
                $homeNav.removeClass('header');
                $chevronDown.removeClass('header');


                $nav.addClass('not-visible').removeClass('visible');
            } else {
                $homeBrandLogo.addClass('header');
                $homeNav.addClass('header');
                $chevronDown.addClass('header');
                $nav.removeClass('not-visible').addClass('visible');
            }
        }).resize(function() {
            centerLogo();
        });

        $(".owl-carousel-home").owlCarousel({
            items : 1,
            itemsDesktop : [1199,1],
            itemsDesktopSmall : [979,1],
            itemsTablet: [768, 1]
        });

        centerLogo();
    };
}
simpleJSRoutingManager.srmController(srmHomeController);

export default srmHomeController;