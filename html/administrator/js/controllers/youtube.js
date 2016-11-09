$(function () {
    $.AdminLTE.srController('youtube', function () {
        this.index = function() {

            var oDatatable = $.AdminLTE.simpleRouting.dsDatatable;
            oDatatable.run('#youTubeTable',function (dataTable){

                var clickDelete = function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var isConfirm = $.AdminLTE.simpleRouting.confirm.show('¿Seguro que deseas eliminar esta video?');

                    var ajaxSuccessEliminar = function(data) {
                        var message = '';
                        var title = '';
                        var type = '';

                        if(data.status == 'ok') {
                            dataTable.api().draw(false);
                            type = 'success';
                        }

                        if(typeof data.message != 'undefined') {
                            message = data.message;
                        }

                        if(data.status == 'ko') {
                            title = 'Error';
                            type = 'danger';
                            message = '';
                            for(var e = 0; e < data.error.errors.length; e++) {
                                message += data.error.errors[e].message + "<br>";
                            }
                            console.log(data);
                        }

                        var calloutParams = [
                            message,title,type
                        ];

                        $.AdminLTE.simpleRouting.callout.show.apply(undefined,calloutParams);
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

        this.add = function() {

        };
    });
});