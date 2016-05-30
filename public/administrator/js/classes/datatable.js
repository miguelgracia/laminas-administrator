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
                    'url': document.location.href,
                    "data": function(json) {
                        $.AdminLTE.simpleRouting.ajax.run({
                            url: '/admin/login/check-auth-session'
                        }, function(data) {
                            if(data.response != true) {
                                location.reload();
                            }
                        });
                    }
                },
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
});
