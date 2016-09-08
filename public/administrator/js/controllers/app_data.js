$(function () {
    $.AdminLTE.srController('app_data', function () {
        this.index = function() {

            var oDatatable = $.AdminLTE.simpleRouting.dsDatatable;
            oDatatable.run('#appDataTable',function() {

            });
        };

        this.edit = function() {


        };
    });
});