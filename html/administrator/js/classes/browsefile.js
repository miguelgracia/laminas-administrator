$(function () {
    var selectYoutubeVideo = (function() {
        var _public = {};

        var inputTarget = null;

        var _$ = {};

        function createModal() {
            _$.videoList = $('<table class="table table-striped" id="youtube_videos"></table>');
            _$.modalWrapper = $('<div id="modal_wrapper"></div>');
            _$.modalContent = $('<div id="modal_content">' +
                    '<div class="box box-primary">' +
                        '<div class="box-header with-border">' +
                            '<h3 class="box-title">Videos Youtube</h3>' +
                            '<div class="box-tools pull-right"></div>' +
                        '</div>' +
                        '<div class="box-body"></div>' +
                    '</div>' +
                '</div>');
            _$.modalClose   = $('<button class="btn btn-box-tool" id="modal_close"><i class="fa fa-close"></i></button>');

            _$.modalContent.find('.box-tools').append(_$.modalClose);
            _$.modalContent.find('.box-body').append(_$.videoList);

            _$.modalContent.appendTo(_$.modalWrapper);

            _$.modalWrapper.appendTo(document.body);
        }

        function attachListeners() {
            _$.modalClose.on('click', function () {
                _$.modalWrapper.remove();
            });

            _$.videoList.find('.select-video').on('click',function(e) {
                e.preventDefault();
                inputTarget.value = 'https://www.youtube.com/embed/' + this.dataset.code;
                _$.modalWrapper.remove();
            })
        }

        function printVideos() {
            var videos = JSON.parse($(inputTarget).attr('data-youtube')), $tr;
            var $tbody = $('<tbody></tbody>');
            for(var v in videos) {
                $tr = $('<tr></tr>');
                $tr.append("<td class='video-channel'>" + videos[v].channelTitle + "</td>");
                $tr.append("<td class='video-title'>" + videos[v].title + "</td>");
                $tr.append("<td class='video-thumb'><img src='http://img.youtube.com/vi/"+videos[v].code +"/1.jpg' /></td>");
                $tr.append("<td class='video-id'>" + videos[v].code + "</td>");
                $tr.append("<td class='video-visibility'>" + videos[v].visibility + "</td>");
                $tr.append("<td class='video-button'><button data-code='" + videos[v].code + "' class='btn btn-info select-video'>Seleccionar</button></td>");

                $tbody.append($tr);
            }

            _$.videoList.append($tbody);
        }

        _public.run = function(target) {
            inputTarget = document.getElementById(target);
            createModal();
            printVideos();
            attachListeners();
        };

        return _public;
    })();

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

            var addInputText = function(input, canDelete) {

                canDelete = canDelete || false;

                var $input = $(input);

                var $inputWrap = $('<div class="input-group"></div>');

                $input.wrap($inputWrap);


                var buttonWrapperHtml =
                    '<span class="input-group-btn">' +
                        ((input.dataset.isMultiple == '1' && canDelete) ? '<button data-target="' + $input.attr('id') + '" type="button" class="btn btn-warning btn-flat">Descartar</button>' : '') +
                    ($input.attr('data-youtube') != undefined ? '<button data-target="' + $input.attr('id') + '" class="btn btn-flat btn-danger btn-youtube"><i class="fa fa-youtube"></i></button>' : '')  +
                        '<button data-target="' + $input.attr('id') + '" type="button" class="btn btn-info btn-flat">Buscar</button>' +
                    '</span>';

                var $buttonWrapper = $(buttonWrapperHtml);

                $input.parent().append($buttonWrapper);

                browserInputs[$input.attr('id')] = $input;
                $buttonWrapper.find('button').on('click', function (e) {
                    e.preventDefault();
                    var $this = $(this),
                        target = $this.data('target');

                    if($this.hasClass('btn-warning')) {
                        if($.AdminLTE.simpleRouting.confirm.show('Está seguro de que quiere desvíncular esta imagen?')) {
                            $this.parents('.input-group').parent().parent().remove();
                        }
                    } else if($this.hasClass('btn-youtube')) {
                        selectYoutubeVideo.run(target);
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
                        '</div>' +
                     '</div>');


                $newWrapper.find('.col-xs-12').prepend(newInput[0]);
                $newWrapper.insertBefore($this);

                addInputText(newInput[0],true);
            };

            inputs = document.getElementsByClassName(config.className);

            for(var idx = 0; idx < inputs.length; idx++) {
                addInputText(inputs[idx], idx != 0);
            }

            var addFileButtons = document.getElementsByClassName('allow_multiple_files');
            for(var btn = 0; btn < addFileButtons.length; btn++ ) {
                addFileButtons[btn].addEventListener('click',addInputEvent,false);
            }
        };

    });
});