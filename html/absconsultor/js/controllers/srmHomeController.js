import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmHomeController() {

    this.indexAction = function() {

        var players = {}, youtubePlayerAvailable = false;

        var $slides = $('.bxslider');

        var bxSlider = $slides.bxSlider({
            mode: 'fade',
            adaptiveHeight: true,
            pager: false,
            preloadImages: 'all',
            auto: true,
            pause: 5000
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
            itemsTablet: [768, 1],
            autoHeight: true,
            afterInit: function() {

                var itemsCount = this.$owlItems.length,
                    currentItem;

                var setPlayVideoEvent = function(playerIndex, item) {

                    return function(e) {
                        e.preventDefault();
                        this.style.opacity = '0';

                        if(players[playerIndex].youtubePlayer != false) {

                            if(players[playerIndex].youtubePlayer.getPlayerState() == '1') {
                                players[playerIndex].youtubePlayer.pauseVideo();
                            } else {
                                players[playerIndex].youtubePlayer.playVideo();
                            }
                            return;
                        }

                        var playerData = players[playerIndex].data;

                        var videoId = playerData.item.firstChild.dataset.src;

                        playerData.videoWrapper.insertBefore(playerData.videoContainer, playerData.videoWrapper.firstChild);

                        players[playerIndex].youtubePlayer = new YT.Player(playerData.videoContainer, {
                            events: {
                                'onReady': function(e) {
                                    //e.target.playVideo();
                                },
                                'onStateChange': function() {

                                }
                            }
                        });
                    };
                };

                for (var i = 0; i < itemsCount; i++) {
                    currentItem = this.$owlItems[i];
                    var playerData = {};

                    if(currentItem.firstChild.classList.contains('video')) {

                        playerData.item = this.$owlItems[i];
                        players[i] = {
                            data: playerData,
                            youtubePlayer: false
                        };
                    }
                }
            },
            afterMove: function() {

                if(typeof players[this.prevItem] == 'undefined') {
                    return;
                }

                if(players[this.prevItem].youtubePlayer != false) {
                    players[this.prevItem].youtubePlayer.pauseVideo();
                }
            }
        });

        var youtubeScript = document.createElement('script');
        youtubeScript.src = 'https://www.youtube.com/iframe_api';

        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(youtubeScript, firstScriptTag);

        window.onYouTubeIframeAPIReady = function() {
            youtubePlayerAvailable = true;
        }
    };
}
simpleJSRoutingManager.srmController(srmHomeController);

export default srmHomeController;