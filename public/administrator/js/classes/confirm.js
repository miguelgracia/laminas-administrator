$(function () {
    $.AdminLTE.srClass('confirm', function() {
        this.show = function(message) {
            return confirm(message);
        };
    });
});