/**
 * Temporalmente deprecated
 *
 */
$(function () {
    $.AdminLTE.srClass('plupload', function() {

        var plUploadContainer = document.getElementById('uploader');

        this.init_ = function() { //Plupload no lo usamos actualmente.

            if(plUploadContainer) {
                $(plUploadContainer).pluploadQueue({
                    // General settings
                    runtimes : 'html5,html4',
                    url : "/admin/media/upload",
                    multipart_params: {
                        'module_target': document.getElementById('media_module_target').value,
                        'module_id': document.getElementById('media_module_id').value
                    },
                    chunk_size : '1mb',
                    rename : false,
                    dragdrop: true,

                    filters : {

                        max_file_size : '10mb',

                        mime_types: [
                            {title : "Image files", extensions : "jpg,gif,png"}
                        ]
                    }
                });
            }
        };
    });
});