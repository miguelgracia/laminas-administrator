import simpleJSRoutingManager from './../simple-js-routing-manager';
import capture from 'capture-video-frame';

function srmVideoPosterClass() {

    this.afterController = function() {
        /*var $video = $('video');
        $video.each(function (x, elem) {
            var frame = capture.captureVideoFrame(elem,'jpeg');
            var poster = frame.dataUri;
            elem.setAttribute('poster',poster);
        });*/
    };
}

simpleJSRoutingManager.srmClass(srmVideoPosterClass);

export default srmVideoPosterClass;