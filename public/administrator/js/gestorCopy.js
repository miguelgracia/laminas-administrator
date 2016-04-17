$(function () {
    $.AdminLTE.srClass ('dsDatatable', function () {
        var dataTable;

        var run = function() {
            var $table = $(this);

            $table.css('width','100%');

            var settings = $table.data('settings'),
                headers = settings.headers,
                columns = [],
                $th,
                $trHead = $table.find('thead tr'),
                $trFoot = $table.find('tfoot tr'),
                i;

            for(i in headers) {
                $th = $('<th data-name="' + i + '">' + headers[i].value + '</th>');
                columns.push(headers[i].options);
                $trHead.append($th);
                $trFoot.append($th.clone());
            }

            console.log(columns);

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

            dataTable = $table.DataTable({
                "iDisplayLength": 10,
                "sServerMethod": "post",
                "processing": true,
                "serverSide": true,
                "sDom": "lip<'horizontal-scroll't>ipr",
                "ajax": document.location.href,
                "columns": columns,
                "initComplete": function() {

                    this.api().columns().every(function () {

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
                    });
                }
            });
        };

        this.init = function() {

            $('.datatable').each(function(x,table) {
                run.apply(table);
            });

        };
    });

    $.AdminLTE.srController ('blog', function () {
        this.run = function () {
            console.log('run blog!!');
        };
    });

    $.AdminLTE.srController('especialistas', function() {
        this.run = function() {

        };
    });
});


$(document).ready(function () {

    $.AdminLTE.srRoutes({
        '/home': function() {},
        '/especialistas': ['especialistas','run'],
        '/blog': ['blog','run']
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

