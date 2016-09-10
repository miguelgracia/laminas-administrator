import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmCookieClass() {

    this.afterController = function() {
        var cookieAlert = document.getElementById('cookie_alert'), closeBtn;
        if(cookieAlert) {
            $('#close_cookie_alert').click(function(e) {
                e.preventDefault();
                Cookies.set('cookie_alert',true);
                cookieAlert.classList.add('hide');
            });
            cookieAlert.classList.remove('hide')
        }
    };
}

simpleJSRoutingManager.srmClass(srmCookieClass);

export default srmCookieClass;