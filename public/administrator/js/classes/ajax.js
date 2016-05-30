$(function () {
    $.AdminLTE.srClass('ajax', function() {

        var defaultOptions = {
            method: 'POST',
            dataType: 'json'
        };

        this.run = function (options, callbackSuccess) {

            $.extend(defaultOptions, options);

            $.ajax(defaultOptions).success(callbackSuccess);
        };
    });
});