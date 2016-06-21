var urlobj;

function SetUrl( url, width, height, alt )
{
    console.log(urlobj);
    document.getElementById(urlobj).value = url ;
    oWindow = null;
}

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
// by: Pete Forde <pete@unspace.ca> @ Unspace Interactive

            function BrowseServer(obj)
            {
                urlobj = obj;
                OpenServerBrowser(
                    '/admin/media?modal=on',
                    screen.width * 0.7,
                    screen.height * 0.7 ) ;
            }

            function OpenServerBrowser( url, width, height )
            {
                var iLeft = (screen.width - width) / 2 ;
                var iTop = (screen.height - height) / 2 ;
                var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes" ;
                sOptions += ",width=" + width ;
                sOptions += ",height=" + height ;
                sOptions += ",left=" + iLeft ;
                sOptions += ",top=" + iTop ;
                var oWindow = window.open( url, "BrowseWindow", sOptions ) ;
            }

            $('#key').on('focus', function () {
                BrowseServer($(this).attr('id'));
            });
        };
    });
});