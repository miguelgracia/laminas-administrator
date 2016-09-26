import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmJobController() {

    this.indexAction = function() {};

    this.categoryAction = function() {};

    this.detailAction = function() {
        var $carousel = $("#owl_carousel");
        var srmObj = this, videoPlayerClass;

        var carouselCallback = function(eventType) {
            return function(elem) {
                var $carousel = $('#owl_carousel').find('.owl-wrapper');

                var currentItem = $carousel.children().eq(this.currentItem),
                    video = currentItem.find('video'),
                    hasVideo = video.length > 0;

                if(hasVideo) {
                    if(currentItem.find('.video-controls').length == 0) {
                        //añadimos los controlles de video
                        videoPlayerClass = srmObj.srm.setClass('srmVideoPlayerClass');
                        videoPlayerClass.run(currentItem);
                    } else {
                        videoPlayerClass.pause(currentItem);
                    }
                }
            };
        };

        $carousel.owlCarousel({
            video: true,
            autoHeight:true,
            singleItem: true,
            stopOnHover: true,
            items : 1,
            afterInit: carouselCallback('afterInit'),
            afterMove: carouselCallback('afterMove'),
            afterUpdate: carouselCallback('afterUpdate')
        });
    };
}
simpleJSRoutingManager.srmController(srmJobController);

export default srmJobController;
