import simpleJSRoutingManager from './../simple-js-routing-manager';
import Viewer from 'viewerjs';

function srmJobController() {

    this.indexAction = function() {};

    this.categoryAction = function() {};

    this.detailAction = function() {
        var viewerOpts = {
            'rotatable': false,
            'fullscreen': false,
            'scalable': false,
            'toolbar': false,
            'title': false
        };
        var gallery = document.getElementById('gallery');
        var items = gallery.querySelectorAll('.img-responsive');

        for(var item = 0; item < items.length; item++) {
            new Viewer(items[item],viewerOpts);
        }
    };
}
simpleJSRoutingManager.srmController(srmJobController);

export default srmJobController;