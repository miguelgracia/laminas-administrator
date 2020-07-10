import simpleJSRoutingManager from './../simple-js-routing-manager';
import animateScrollTo from "animated-scroll-to";

function srmAccessoryController() {

    const goToTop = function () {

        [].forEach.call(document.querySelectorAll('.gotop'), function (gotop) {
            gotop.addEventListener('click', function (event) {
                event.preventDefault();
                animateScrollTo(0);
                return false;
            });
        });
    };

    this.indexAction = function() {
        goToTop();
    };

    this.categoryAction = function() {
        goToTop();
    };

    this.detailAction = function() {

    };
}
simpleJSRoutingManager.srmController(srmAccessoryController);

export default srmAccessoryController;