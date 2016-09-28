import simpleJSRoutingManager from './../simple-js-routing-manager';
import Viewer from 'viewerjs';

function srmJobController() {

    this.indexAction = function() {};

    this.categoryAction = function() {};

    this.detailAction = function() {
        var viewer = new Viewer(document.getElementById('gallery'),{
            'rotatable': false,
            'fullscreen': false,
            'scalable': false,
            'toolbar': false,
            'title': false
        });
    };
}
simpleJSRoutingManager.srmController(srmJobController);

export default srmJobController;