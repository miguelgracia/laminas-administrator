$(function () {
    $.AdminLTE.srController('blog', function () {
        this.index = function() {

            var oDatatable = $.AdminLTE.simpleRouting.dsDatatable;
            oDatatable.run('#blogTable',function (dataTable){

                var clickDelete = function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var isConfirm = $.AdminLTE.simpleRouting.confirm.show('¿Seguro que deseas eliminar esta entrada de blog?');

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
            $("#uploader").pluploadQueue({
                // General settings
                runtimes : 'html5,flash,silverlight,html4',
                url : "/examples/upload",

                chunk_size : '1mb',
                rename : true,
                dragdrop: true,

                filters : {
                    // Maximum file size
                    max_file_size : '10mb',
                    // Specify what files to browse for
                    mime_types: [
                        {title : "Image files", extensions : "jpg,gif,png"},
                        {title : "Zip files", extensions : "zip"}
                    ]
                },

                // Resize images on clientside if we can
                resize: {
                    width : 200,
                    height : 200,
                    quality : 90,
                    crop: true // crop to exact dimensions
                },


                // Flash settings
                flash_swf_url : '/plupload/js/Moxie.swf',

                // Silverlight settings
                silverlight_xap_url : '/plupload/js/Moxie.xap'
            });
        };
    });
});