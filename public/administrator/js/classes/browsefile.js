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

            inputs = document.getElementsByClassName(config.className);

            for(var idx = 0; idx < inputs.length; idx++) {
                var $input = $(inputs[idx]);

                var $inputWrap = $('<div class="input-group"></div>');

                $input.wrap($inputWrap);

                var $buttonWrapper = $('<span class="input-group-btn"><button data-target="'+$input.attr('id')+'" type="button" class="btn btn-info btn-flat">Buscar</button></span>');

                $input.parent().append($buttonWrapper);

                browserInputs[$input.attr('id')] = $input;
                $buttonWrapper.find('button').on('click', function () {
                    var $this = $(this),
                        target = $this.data('target');

                    browseServer($('#'+target));
                });
            }
        };

    });
});