$(function () {
    $.AdminLTE.srClass('wysiwyg', function() {

        var textarea = document.getElementsByTagName('textarea');

        this.init = function() {
            var applyCKEditorEvents = false;

            var setBrowseButton = function (browseButton, event) {
                var cleanUpFuncRef = CKEDITOR.tools.addFunction(function ()
                {
                    // Do the clean-up of filemanager here (called when an image was selected or cancel was clicked)
                    $('#filemanager_iframe').remove();
                    $("body").css("overflow-y", "scroll");
                });

                var editor = event.editor;

                browseButton.hidden = false;
                browseButton.onClick = function (dialog, i)
                {
                    editor._.filebrowserSe = this;
                    var base_url = document.location.protocol + '//' + document.location.host + '/admin/media';
                    var iframe = $("<iframe id='filemanager_iframe' class='fm-modal'/>").attr({
                        src: base_url + // Change it to wherever  Filemanager is stored.
                        '?modal=on&CKEditorFuncNum=' + CKEDITOR.instances[event.editor.name]._.filebrowserFn +
                        '&CKEditorCleanUpFuncNum=' + cleanUpFuncRef +
                        '&langCode=es' +
                        '&CKEditor=' + event.editor.name
                    });

                    $("body")
                        .css("overflow-y", "hidden") // Get rid of possible scrollbars in containing document
                        .append(iframe);
                }
            };
            for(var idx = 0; idx < textarea.length; idx++) {

                if(!textarea[idx].classList.contains('no-editor')) {
                    applyCKEditorEvents = true;
                    CKEDITOR.config.extraAllowedContent = 'video(*){*}[*];source(*){*}[*];img(*){*}[*]';
                    CKEDITOR.replace(textarea[idx].getAttribute('id'));
                }
            }

            if(applyCKEditorEvents) {

                CKEDITOR.on('dialogDefinition', function (event)
                {
                    var dialogDefinition = event.data.definition;
                    var dialogName = event.data.name;

                    var tabCount = dialogDefinition.contents.length;

                    for (var i = 0; i < tabCount; i++) {
                        var browseButton;

                        if(dialogName == 'video') {
                            var elements = dialogDefinition.contents[i].elements;
                            var elementsCount = elements.length;

                            if(typeof dialogDefinition.contents[i] != 'undefined') {
                                for(var e = 0; e < elementsCount; e++) {
                                    var hboxElement = dialogDefinition.contents[i].elements[e];
                                    if(typeof hboxElement.children != 'undefined') {
                                        var hboxChildrenCount = hboxElement.children.length;
                                        for(var h = 0; h < hboxChildrenCount; h++) {
                                            if(typeof hboxElement.children[h].filebrowser != 'undefined') {
                                                browseButton = hboxElement.children[h];
                                                setBrowseButton(browseButton, event);
                                            }
                                        }
                                    }
                                }
                            }
                        } else {

                            if(typeof dialogDefinition.contents[i] != 'undefined') {
                                browseButton = dialogDefinition.contents[i].get('browse');

                                if (browseButton !== null) {
                                    setBrowseButton(browseButton, event);
                                }
                            }
                        }
                    }



                }); // dialogDefinition*/
            }
        };
    });
});
