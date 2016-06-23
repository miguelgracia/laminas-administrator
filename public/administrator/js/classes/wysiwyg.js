$(function () {
    $.AdminLTE.srClass('wysiwyg', function() {

        var textarea = document.getElementsByTagName('textarea');

        this.init = function() {
            var applyCKEditorEvents = false;
            for(var idx = 0; idx < textarea.length; idx++) {

                if(!textarea[idx].classList.contains('no-editor')) {
                    applyCKEditorEvents = true;
                    CKEDITOR.replace(textarea[idx].getAttribute('id'));
                }
            }

            if(applyCKEditorEvents) {
                CKEDITOR.on('dialogDefinition', function (event)
                {
                    var editor = event.editor;
                    var dialogDefinition = event.data.definition;
                    var dialogName = event.data.name;

                    var cleanUpFuncRef = CKEDITOR.tools.addFunction(function ()
                    {
                        // Do the clean-up of filemanager here (called when an image was selected or cancel was clicked)
                        $('#filemanager_iframe').remove();
                        $("body").css("overflow-y", "scroll");
                    });

                    var tabCount = dialogDefinition.contents.length;

                    for (var i = 0; i < tabCount; i++) {

                        var browseButton;

                        if(typeof dialogDefinition.contents[i] != 'undefined') {
                            browseButton = dialogDefinition.contents[i].get('browse')
                        }

                        if (browseButton !== null) {
                            browseButton.hidden = false;
                            browseButton.onClick = function (dialog, i)
                            {
                                editor._.filebrowserSe = this;
                                var iframe = $("<iframe id='filemanager_iframe' class='fm-modal'/>").attr({
                                    src: 'http://abs.local/admin/media' + // Change it to wherever  Filemanager is stored.
                                    '?modal=on&CKEditorFuncNum=' + CKEDITOR.instances[event.editor.name]._.filebrowserFn +
                                    '&CKEditorCleanUpFuncNum=' + cleanUpFuncRef +
                                    '&langCode=es' +
                                    '&CKEditor=' + event.editor.name
                                });

                                $("body").append(iframe);
                                $("body").css("overflow-y", "hidden");  // Get rid of possible scrollbars in containing document
                            }
                        }
                    }
                }); // dialogDefinition*/
            }
        };
    });
});
