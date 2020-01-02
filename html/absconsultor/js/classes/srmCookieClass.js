import simpleJSRoutingManager from './../simple-js-routing-manager';

function srmCookieClass() {

    this.afterController = function() {
        var cookieAlert = document.getElementById('cookie_alert');
        if(cookieAlert) {
            const closeBtn = document.getElementById('close_cookie_alert');
            closeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                Cookies.set('cookie_alert',true, {expires: 365 * 5});
                cookieAlert.classList.add('hide');
            });

            cookieAlert.classList.remove('hide');
        }
    };
}

simpleJSRoutingManager.srmClass(srmCookieClass);

export default srmCookieClass;