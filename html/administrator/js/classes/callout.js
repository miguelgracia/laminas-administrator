$(function () {
    $.AdminLTE.srClass('callout', function () {

        var $dsCallout, $applicationContent;

        this.init = function() {
            $applicationContent = $('#admin_app');

            $dsCallout = $('#ds_callout');

            if($dsCallout.length == 0) {
                $dsCallout = $('<div id="ds_callout"></div>');
                $dsCallout.prependTo($applicationContent);
            }
        };

        this.show = function(message,title, type) {
            type = typeof type == 'undefined' ? 'success' : type;
            title = typeof title == 'undefined' ? '&nbsp' : title;

            var $tpl =
                $("<div class='alert-dismissable alert alert-" + type + "'>" +
                    "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                    "<h4>" + title + "</h4>" +
                    "<p>" + message + "</p>" +
                    "</div>");

            setTimeout(function() {
                $tpl.fadeOut(function() {
                    $(this).remove()
                });
            },5000);

            $dsCallout.append($tpl);
        };
    });
});