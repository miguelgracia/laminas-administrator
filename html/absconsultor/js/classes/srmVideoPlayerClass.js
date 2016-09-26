import simpleJSRoutingManager from './../simple-js-routing-manager';
import capture from 'capture-video-frame';

function srmVideoPlayerClass() {

    var $controlsWrapper, controls, callbackControls,
        _$wrapper,video;

    this.pause = function($wrapper) {
        video = $wrapper.find('video')[0];
        if(!video.paused) {
            console.log(controls);
            controls.pause.trigger('click');
            controls.pause.addClass('hide');
            controls.play.removeClass('hide');
        }
    };

    this.run = function($wrapper) {
        _$wrapper = $wrapper;

        $controlsWrapper = $('<div class="video-controls transition"></div>');

        video = $wrapper.find('video')[0];

        controls = {
            'play': $('<i class="play-button fa fa-play"></i>'),
            'pause': $('<i class="pause-button fa fa-pause hide"></i>')
        };

        callbackControls = {
            'reproduction': function(controlType) {
                var inverse = controlType == 'play' ? 'pause' : 'play';
                return function(e) {
                    e.preventDefault();

                    controls[controlType].addClass('hide');
                    controls[inverse].removeClass('hide');
                    video[controlType]();
                };
            }
        };

        $wrapper.on('mouseleave', function () {
            if(!video.paused) {
                $controlsWrapper.addClass('hide-control');
            }
        }).on('mouseenter',function() {
            $controlsWrapper.removeClass('hide-control');
        });

        controls.play.on('click', callbackControls.reproduction('play'));
        controls.pause.on('click', callbackControls.reproduction('pause'));

        controls.play.appendTo($controlsWrapper);
        controls.pause.appendTo($controlsWrapper);

        $controlsWrapper.appendTo($wrapper);
    };
}

simpleJSRoutingManager.srmClass(srmVideoPlayerClass);

export default srmVideoPlayerClass;