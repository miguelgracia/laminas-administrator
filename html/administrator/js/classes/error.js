$(function () {
    $.AdminLTE.srClass('error', function() {
        this.init = function() {
            var $errors = $('.control-label.error'),
                $langTabButtons = $('.nav-tabs.languages');
            if($errors.length > 0) {
                $errors.each(function (x, elem) {
                    var $tabPane = $(elem).parents('.tab-pane');

                    if($tabPane.length == 0 || $tabPane.hasClass('active')) {
                        return false;
                    }

                    $langTabButtons.find('a[href="#'+$tabPane.attr('id')+'"]').trigger('click');
                });
            }
        };
    });
});