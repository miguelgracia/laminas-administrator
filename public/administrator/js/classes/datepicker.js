$(function () {
    $.AdminLTE.srClass('datepicker', function() {
        this.init = function() {
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });
        };
    });
});