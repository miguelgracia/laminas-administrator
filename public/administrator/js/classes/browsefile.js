$(function () {
    $.AdminLTE.srClass('browsefile', function() {

        var config = {
            className: 'browsefile',
            iframeUrl: '/admin/media?modal=on'
        };

        var oWindow,
            browserInputs = {},
            $inputTarget = null;

        function OpenServerBrowser( url, width, height )
        {
            var iLeft = (screen.width - width) / 2 ;
            var iTop = (screen.height - height) / 2 ;
            var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes" ;
            sOptions += ",width=" + width ;
            sOptions += ",height=" + height ;
            sOptions += ",left=" + iLeft ;
            sOptions += ",top=" + iTop ;
            oWindow = window.open( url, "BrowseWindow", sOptions ) ;
        }

        function browseServer(obj)
        {
            $inputTarget = obj;
            OpenServerBrowser(
                config.iframeUrl,
                screen.width * 0.7,
                screen.height * 0.7 ) ;
        }

        this.init = function() {
            var inputs;

            window.SetUrl = function ( url, width, height, alt )
            {
                $inputTarget.val(url);
                oWindow = null;
            };

            var addInputText = function(input) {
                var $input = $(input);

                var $inputWrap = $('<div class="input-group"></div>');

                $input.wrap($inputWrap);

                var buttonWrapperHtml =
                    '<span class="input-group-btn">' +
                        (input.dataset.isMultiple == '1' ? '<button data-target="' + $input.attr('id') + '" type="button" class="btn btn-danger btn-flat">Borrar</button>' : '')+
                        '<button data-target="' + $input.attr('id') + '" type="button" class="btn btn-info btn-flat">Buscar</button>' +
                    '</span>';

                var $buttonWrapper = $(buttonWrapperHtml);

                $input.parent().append($buttonWrapper);

                browserInputs[$input.attr('id')] = $input;
                $buttonWrapper.find('button').on('click', function () {
                    var $this = $(this),
                        target = $this.data('target');

                    if($this.hasClass('btn-danger')) {
                        if($.AdminLTE.simpleRouting.confirm.show('Está seguro de que quiere desvíncular esta imagen?')) {
                            $this.parents('.input-group').parent().parent().remove();
                        }
                    } else {
                        browseServer($('#'+target));
                    }
                });
            };

            var addInputEvent = function () {
                var $this = $(this);
                var newInput = $this.prev().find('.browsefile').clone();
                    newInput[0].removeEventListener('click',addInputEvent,false);

                var newIndex = parseInt(newInput.attr('data-index')) + 1;
                newInput.attr('data-index', newIndex);
                newInput.attr('id', newInput.attr('data-id') + newIndex);
                newInput.val('');

                var $newWrapper =
                    $('<div class="form-group">' +
                        '<div class="col-xs-12">' +
                        '<div class="input-group">' +
                        '<span class="input-group-btn"></span>' +
                        '</div>' +
                        '</div>' +
                        '</div>');

                $newWrapper.find('.input-group').prepend(newInput);
                $newWrapper.insertBefore($this);

                addInputText(newInput[0]);
            };

            inputs = document.getElementsByClassName(config.className);

            for(var idx = 0; idx < inputs.length; idx++) {
                addInputText(inputs[idx]);
            }

            var addFileButtons = document.getElementsByClassName('allow_multiple_files');
            for(var btn = 0; btn < addFileButtons.length; btn++ ) {
                addFileButtons[btn].addEventListener('click',addInputEvent,false);
            }
        };

    });
});