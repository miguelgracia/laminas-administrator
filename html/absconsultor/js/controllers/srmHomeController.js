import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmHomeController() {

    this.indexAction = function() {

        var $slides = $('.bxslider'), video;

        var sliderEventCallbacks = function(eventType) {

            return function ($slideElement, oldIndex) {

                var $videoWrapper, $play, $pause;

                if(eventType == 'onSliderLoad') {
                    //la variable $slideElement, en este caso es igual al indice de la primera slide que carga
                    $videoWrapper = $slides.eq($slideElement).find('.video-wrapper');
                } else {
                    $videoWrapper = $slideElement.find('.video-wrapper');
                }

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
                                    if(video.paused) {
                                        video.play();
                                    }
                                    break;
                                case 'pause':
                                    $play.removeClass('hide');
                                    $pause.addClass('hide');
                                    if(video.played) {
                                        video.pause();
                                    }
                                    break;
                            }
                        };
                    };

                    $play.click(videoControlEvents('play'));
                    $pause.click(videoControlEvents('pause'));

                    if(video) {
                        //$play.trigger('click');
                    }
                }
            };
        };

        var bxSlider = $slides.bxSlider({
            mode: 'fade',
            adaptiveHeight: true,
            pager: false,
            video: true,
            preloadImages: 'all',
            onSliderLoad: sliderEventCallbacks('onSliderLoad'),
            onSlideAfter: sliderEventCallbacks('onSlideAfter')
        });

        var $nav = $('nav');

        var $homeBrandLogo = $('#home_brand_logo'),
            $homeNav = $(".home-navbar.nav"),
            $chevronDown = $(".fa.fa-chevron-down");

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
        });

        $(".owl-carousel-home").owlCarousel({
            items : 1,
            itemsDesktop : [1199,1],
            itemsDesktopSmall : [979,1],
            itemsTablet: [768, 1]
        });
    };
}
simpleJSRoutingManager.srmController(srmHomeController);

export default srmHomeController;