$(function () {
    $.AdminLTE.srController('static_page', function () {
        this.index = function() {

            var oDatatable = $.AdminLTE.simpleRouting.dsDatatable;
            oDatatable.run('#staticPageTable',function (dataTable){

                var clickDelete = function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var isConfirm = $.AdminLTE.simpleRouting.confirm.show('¿Seguro que deseas eliminar esta página?');

                    var ajaxSuccessEliminar = function(data) {
                        if(data.status == 'ok') {
                            dataTable.api().draw(false);
                        }
                        if(typeof data.message != 'undefined') {
                            $.AdminLTE.simpleRouting.callout.show(data.message);
                        }
                    };
                    if(isConfirm) {
                        $.AdminLTE.simpleRouting.ajax.run({
                            url: $this.parent().attr('href')
                        }, ajaxSuccessEliminar);
                    }
                };

                $(document.body)
                    .on('click','.js-eliminar',clickDelete);
            });
        };

        this.edit = function() {


        };
    });
});