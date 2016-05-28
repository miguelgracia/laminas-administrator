$(function () {
    $.AdminLTE.srClass ('dsDatatable', function () {
        var dataTable,
            oDataTable = {},
            language  = {
                "decimal":        "",
                "emptyTable":     "No hay datos disponibles",
                "info":           "Viendo _START_ de _END_ de un total de _TOTAL_ registros",
                "infoEmpty":      "Viendo 0 de 0 de un total de 0 registros",
                "infoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing":     "Procesando...",
                "search":         "Buscar:",
                "zeroRecords":    "No se han encontrado resultados",
                "paginate": {
                    "first":      "Primera",
                    "last":       "Última",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            };

        this.run = function(tableId, customConfig, initCompleteFn) {

            var $table = $(tableId);

            if(typeof customConfig == 'function') {
                initCompleteFn = customConfig;
                customConfig = {};
            }

            $table.css('width','100%');

            var settings = $table.data('settings'),
                headers = settings.headers,
                columns = [],
                order = [],
                $th,
                $trHead = $table.find('thead tr'),
                $trFoot = $table.find('tfoot tr'),
                i,
                counter = 0;

            for(i in headers) {
                var headerAttributes = headers[i].headerAttributes, headerAttr,
                    jsonKey = typeof headers[i].json_key == 'undefined' ? i : headers[i].json_key;

                $th = $('<th data-name="' + i + '">' + headers[i].value + '</th>');
                headers[i].options.data = jsonKey;
                columns.push(headers[i].options);

                if(typeof headers[i].order != 'undefined') {
                    order.push([counter,headers[i].order]);
                }

                for(headerAttr in headerAttributes) {
                    $th.attr(headerAttr,headerAttributes[headerAttr]);
                }

                $trHead.append($th);
                $trFoot.append($th.clone());
                counter++;
            }

            $trHead.first().find('th').each(function (x, header) {
                var $header = $(header),
                    title = $header.text(),
                    columnName = $header.data('name'),
                    inputElem = '';

                if(typeof columns[$header.index()] !== 'undefined' && columns[$header.index()].searchable) {

                    if(typeof settings.dropdown_filters != 'undefined' && typeof settings.dropdown_filters[columnName] != 'undefined') {
                        var options = settings.dropdown_filters[columnName], c,
                            $select = '<select>';

                        $select += '<option value="">' + title + '</option>';
                        for(c in options) {
                            $select += '<option value="' + c + '">' + options[c] + '</option>';
                        }
                        $select += '</select>';

                        inputElem = $select;
                    } else {
                        inputElem = '<input type="text" placeholder="' + title + '" />'
                    }
                }
                $header.html(inputElem);
            });

            var defaultConfig = {
                "iDisplayLength": 10,
                "sServerMethod": "post",
                "processing": true,
                "serverSide": true,
                "sDom": "lip<'horizontal-scroll't>ipr",
                "ajax": {
                    'url': document.location.href
                },
                "dataSrc": 'data',
                "language": language,
                "columns": columns,
                "order": order,
                "rowCallback": function(row, data, index) {}
            };

            $.extend(defaultConfig, customConfig);

            //initComplete lo sobreescribimos siempre, ya que tiene lógica de existir siempre
            //pero tenemos la opción de entrar en ella desde el parametro initCompleteFn

            defaultConfig.initComplete = function() {

                var everyFn = function () {

                    var that = this,
                        $header = $(this.header()),
                        headerIndex = $header.index(),
                        $headerFilter = $header.parent().prev().children().eq(headerIndex);

                    $( 'input, select', $headerFilter).on( 'keyup change', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    } );
                };

                this.api().columns().every(everyFn);

                if(typeof initCompleteFn == 'function') {
                    initCompleteFn(this);
                }
            };

            dataTable = $table.DataTable(defaultConfig);

            return dataTable;
        };

        return this;
    });

    $.AdminLTE.srClass('ajax', function() {

        var defaultOptions = {
            method: 'POST',
            dataType: 'json'
        };

        this.run = function (options, callbackSuccess) {

            $.extend(defaultOptions, options);

            $.ajax(defaultOptions).success(callbackSuccess);
        };
    });

    $.AdminLTE.srClass('callout', function () {

        var $dsCallout, $applicationContent;

        this.init = function() {
            $applicationContent = $('#ds_application_content');

            $dsCallout = $(document.getElementById('ds_callout'));

            if($dsCallout.length == 0) {
                $dsCallout = $('<div id="ds_callout"></div>');
                $dsCallout.prependTo($applicationContent);
            }
        };

        this.show = function(message,title, type) {
            type = typeof type == 'undefined' ? 'success' : type;
            title = typeof title == 'undefined' ? '&nbsp' : title;

            var $tpl =
                $("<div class='alert-dismissable alert alert-" + type + "'>" +
                    "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                    "<h4>" + title + "</h4>" +
                    "<p>" + message + "</p>" +
                    "</div>");

            setTimeout(function() {
                $tpl.fadeOut(function() {
                    $(this).remove()
                });
            },5000);

            $dsCallout.append($tpl);
        };
    });

    $.AdminLTE.srClass('confirm', function() {
        this.show = function(message) {
            return confirm(message);
        };
    });

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
    });


    $.AdminLTE.srController('user', function () {
        this.index = function() {

            var oDatatable = $.AdminLTE.simpleRouting.dsDatatable;
            oDatatable.run('#userTable',function (dataTable){

                var clickDelete = function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var isConfirm = $.AdminLTE.simpleRouting.confirm.show('¿Seguro que deseas eliminar este usuario?');

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
                    .on('click','.js-eliminar-usuario',clickDelete);
            });
        };
    });

    $.AdminLTE.srController('menu', function () {

        this.index = function() {
            var menuAccordion = document.getElementById("menu_accordion");
            var $listGroupSubMenu = $('.list-group.sub-menu');

            var updateOrderCallback = function(e) {

                var $target = $(e.originalTarget);
                var elementIds = [];
                $target.children().each(function (x, elem) {
                    elementIds.push($(elem).data('id'));
                });
                $.AdminLTE.simpleRouting.ajax.run({
                    url: '/admin/menu/save-order',
                    data: {
                        elements:elementIds
                    }
                }, function(data) {

                });
            };

            Sortable.create(menuAccordion, {
                "draggable": ".panel.box",
                "onUpdate": updateOrderCallback
            });
            $listGroupSubMenu.each(function (x, elem){
                Sortable.create(elem,{
                        "onUpdate": updateOrderCallback
                });
            });

            $(menuAccordion).find('.btn-remove').click(function(e) {
                e.preventDefault();
                var canDelete = $.AdminLTE.simpleRouting.confirm.show("¿Deseas eliminar esta opción de menú?");
                if(canDelete) {
                    document.location.href = $(this).attr('href');
                }
            });
        };

        this.addAndedit = function() {
            var $accion = $('#accion').select2({width: '100%'});

            var $optGroupAccion = $accion.find('optgroup');

            var $gestorModuleId = $('#gestorModuleId').select2({width: '100%'});

            var changeModuleId = function(e) {
                var optionSelected = $(e.target).find('option:selected').text();
                $optGroupAccion.addClass('hide').find('option').attr('disabled','disabled');

                $optGroupAccion.filter('[label="'+optionSelected+'"]').find('option').removeAttr('disabled');

                $accion.select2();
            };

            $gestorModuleId.on('change',changeModuleId);
        };
    });

});

$(document).ready(function () {

    moment.locale('es');

    $.AdminLTE.srRoutes({
        '/home': function() {},
        '/admin/user':                   ['user','index'],
        '/admin/menu/edit/{:num}':       ['menu','addAndedit'],
        '/admin/menu/add/{:num}':        ['menu','addAndedit'],
        '/admin/menu':                   ['menu','index'],
        '/admin/blog':                   ['blog','index']
    }).run();

    $(".delete_alert").click(function() {
        var _attr = $(this).attr('texto');
        if (confirm(_attr)) {
            $(this).parent().submit();
        } else {
            return false;
        }
    });

});

