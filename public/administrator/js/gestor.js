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

    $.AdminLTE.srClass('selectEspecialista', function() {

        var _this = this,
            $selectEspecialistas = $('.js-select-especialistas'),
            imagenEspecialista;

        _this.getFoto = function() {
            return imagenEspecialista;
        };

        _this.val = function(value) {
            $selectEspecialistas.val(value);
        };

        _this.run = function() {
            var events = {
                changeSelectEspecialista: function (e) {
                    var $this = $(this),
                        successFn = function(data) {
                            var $imageWrapper = $this.parents('.js-especialista').find('.js-foto-especialista');

                            if(data.status == 'ok') {
                                imagenEspecialista = data.result !== '' ? '<img src="' + data.result + '" class="img-responsive pull-center" />' : '';
                            } else {
                                imagenEspecialista = data.error;
                            }


                            $imageWrapper.html(imagenEspecialista);
                        };

                    $.AdminLTE.simpleRouting.ajax.run({
                        url: '/especialistas/foto/' + $this.val()
                    }, successFn);
                }
            };

            $selectEspecialistas.on('change', events.changeSelectEspecialista);

            return _this;
        };
    });

    $.AdminLTE.srController('especialistas', function() {
        this.listado = function() {
            var oDatatable = $.AdminLTE.simpleRouting.dsDatatable;
            oDatatable.run('#especialistaTable',function (dataTable){

                var changeActivation = function (e) {
                    e.preventDefault();

                    var $this = $(this),
                        newStatus = 0;

                    if($this.hasClass('activar')) {
                        newStatus = 1;
                    }

                    var ajaxSuccess = function(data) {
                        if(data.status == 'ok') {
                            if($this.hasClass('activar')) {
                                $this.removeClass('activar fa-square-o').addClass('desactivar fa-check-square-o');
                            } else {
                                $this.addClass('activar fa-square-o').removeClass('desactivar fa-check-square-o');
                            }
                            dataTable.api().draw(false);
                        } else {
                            alert(data.error);
                        }
                    };

                    $.AdminLTE.simpleRouting.ajax.run({
                        url: $this.parent().attr('href'),
                        data: {
                            newStatus: newStatus
                        }
                    }, ajaxSuccess);
                };

                var parseResult = {
                    'equipos': function(data) {
                        var html = '', d;
                        for(d in data) {
                            var especialistas = data[d].especialistas;
                            var htmlEsp = '';

                            for(var esp in especialistas) {

                                var foto = '/upload/especialista/' + especialistas[esp].foto;

                                var regEx = new RegExp("(.*)\.(png|gif|jpg|jpeg)$","gi"),
                                    matchFoto = regEx.exec(foto);
                                if(matchFoto != null) {
                                    foto = matchFoto[1] + '_thumb.' + matchFoto[2];
                                }
                                if ((parseInt(esp) + 1) % 3 == 0) {
                                    htmlEsp += '<div class="row">';
                                }
                                htmlEsp +=
                                    "<div class='col-sm-4 col-xs-4 js-especialista'>" +
                                    "<div class='box box-default'>" +
                                    "<div class='box-header text-center with-border'>" +
                                    "<strong><small>"+especialistas[esp].especialidad+"</small></strong>" +
                                    "</div>" +
                                    "<div class='box-body text-center'>" +
                                    "<div class='row'>" +
                                    "<div class='col-xs-12'>" +
                                    especialistas[esp].nombreEspecialista +
                                    "</div>" +
                                    "</div>" +
                                    "<div class='row'>" +
                                    "<div class='col-xs-12 js-foto-especialista'>" +
                                    "<img data-foto='"+foto+"' class='img-responsive pull-center' src='' />" +
                                    "</div>" +
                                    "</div>" +
                                    "</div>" +
                                    "</div>" +
                                    "</div>";
                                if ((parseInt(esp) + 1) % 3 == 0) {
                                    htmlEsp += '</div>';
                                }
                            }

                            html += '<div class="row">' +
                                '<div class="col-xs-12">' +
                                '<div class="box">' +
                                '<div class="box-header">' +
                                '<div class="row">' +
                                '<div class="col-xs-6">' +
                                'Equipo ' + data[d].idEquipo + '<br/>' + data[d].director +
                                '</div>' +
                                '<div class="col-xs-6">' +
                                '<a class="btn btn-info btn-xs pull-right margin" href="/director_relacion/equipos/' + data[d].idDirector+ '#'+data[d].idEquipo+'">Editar Equipo</a> ' +
                                '<a data-equipo="' + data[d].idEquipo + '" class="btn btn-success btn-xs pull-right margin js-ver-equipo">Ver Equipo</a> ' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="hide box-body js-modal-equipo-' + data[d].idEquipo + '">' +
                                htmlEsp +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        }
                        return html;
                    },
                    'directores': function(data) {
                        var html = '', d, directores = {};

                        for(d in data) {
                            var key = data[d].idDirector + data[d].director;

                            if(typeof directores[key] == 'undefined') {
                                directores[key] = {
                                    'idEquipo': data[d].idEquipo,
                                    'idDirector': data[d].idDirector,
                                    'director': data[d].director,
                                    'equipos': []
                                };
                            }
                            directores[key].equipos.push(data[d]);
                        }

                        for(d in directores) {
                            html +=
                                '<div class="row">' +
                                '<div class="col-xs-12">' +
                                '<div class="box">' +
                                '<div class="box-header">' +
                                '<div class="row">' +
                                '<div class="col-xs-12">' +
                                directores[d].director + '<br/>Equipos con este especialista: ' + directores[d].equipos.length +
                                '<a class="btn btn-info btn-xs pull-right margin" href="/director_relacion/equipos/' + directores[d].idDirector+ '">Editar Equipos</a> ' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        }
                        return html;
                    }
                };

                var launchModal = function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var ajaxSuccess = function(data) {
                        var $modal = $('#datatable_modal'),
                            content = data.result.content,
                            content = parseResult[data.result.tipo](content);

                        $modal.find('.modal-title').html(data.result.title);
                        $modal.find('.modal-body').html(content);


                        $modal.on("show.bs.modal", function() {
                            var height = $(window).height() - 200;
                            $(this).find(".modal-body").css("max-height", height);
                        });


                        $modal.modal();
                    };

                    $.AdminLTE.simpleRouting.ajax.run({
                        url: $this.attr('href')
                    }, ajaxSuccess);
                };

                var verEquipo = function(e) {
                    e.preventDefault();
                    var $this = $(this),
                        equipoId = $this.data('equipo'),
                        $modalEquipo = $('.js-modal-equipo-' + equipoId);

                    if($modalEquipo.hasClass('hide')) {
                        $modalEquipo.removeClass('hide');
                        $modalEquipo.find('img').each(function (x, img) {
                            var $img = $(img),
                                src = $img.attr('src');
                            if(src == '') {
                                $img.attr('src',$img.data('foto'));
                            }
                        });
                    } else {
                        $modalEquipo.addClass('hide')
                    }
                };

                var clickDelete = function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var isConfirm = $.AdminLTE.simpleRouting.confirm.show('¿Seguro que desea eliminar este especialista?');

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
                    .on('click', '#especialistaTable .activar', changeActivation)
                    .on('click', '#especialistaTable .desactivar', changeActivation)
                    .on('click', '.js-ver-equipos', launchModal)
                    .on('click', '.js-ver-directores', launchModal)
                    .on('click', '.js-ver-equipo',verEquipo)
                    .on('click','.js-eliminar-especialista',clickDelete);
            });
        };

        this.sustituir = function() {

            var srSelectEspecialista = $.AdminLTE.simpleRouting.selectEspecialista.run();

            var $sustituirEspecialista = $('.js-sustituir-especialista'),
                $fotoEspecialista = $('.js-foto-especialista');

            var events = {
                clickSustituirEspecialista: function(e) {
                    e.preventDefault();
                    var $this = $(this),
                        value = $this.data('rel');

                    var $thisSelect = $('#idespecialista_' + value);

                    srSelectEspecialista.val($thisSelect.val());
                    $fotoEspecialista.html(srSelectEspecialista.getFoto());
                }
            };

            $sustituirEspecialista.on('click',events.clickSustituirEspecialista);
        };
    });

    $.AdminLTE.srController('director_relacion', function() {

        var edicionEquipos = function() {

            var locationHash = document.location.hash;
            var equipoEditado = parseInt($('#js-equipo-editado').val()),
                openTab = 0;

            $.AdminLTE.simpleRouting.selectEspecialista.run();

            if(equipoEditado > 0) {
                openTab = equipoEditado;
            } else if(locationHash != '') {
                openTab = parseInt(locationHash.replace('#',''));
            }

            if(openTab > 0) {
                $('.js-equipo-' + openTab).find('.btn-box-tool').trigger('click');
            }
        };

        this.listado = function() {

            var oDatatable = $.AdminLTE.simpleRouting.dsDatatable;

            oDatatable.run('#directorRelacionTable', function (dataTable) {

                var changeActivation = function (e) {
                    e.preventDefault();

                    var $this = $(this),
                        newStatus = 0;

                    if($this.hasClass('activar')) {
                        newStatus = 1;
                    }

                    var ajaxSuccessActivar = function(data) {
                        if(data.status == 'ok') {
                            if($this.hasClass('activar')) {
                                $this.removeClass('activar fa-square-o').addClass('desactivar fa-check-square-o');
                            } else {
                                $this.addClass('activar fa-square-o').removeClass('desactivar fa-check-square-o');
                            }
                            dataTable.api().draw(false);
                        } else {
                            alert(data.error);
                        }
                    };

                    $.AdminLTE.simpleRouting.ajax.run({
                        url: $this.parent().attr('href'),
                        data: {
                            newStatus: newStatus
                        }
                    }, ajaxSuccessActivar);
                };

                var clickAddressBtn = function(e) {
                    e.preventDefault();
                    var $this = $(this),
                        $address = $this.siblings('span').first();

                    if($address.hasClass('hide')) {
                        $address.removeClass('hide').addClass('show');
                    } else {
                        $address.removeClass('show').addClass('hide');
                    }
                };

                var clickDelete = function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var isConfirm = $.AdminLTE.simpleRouting.confirm.show('¿Seguro que desea eliminar este director de relación?');

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

                $(document.body).on('click', '#directorRelacionTable .activar', changeActivation)
                    .on('click', '#directorRelacionTable .desactivar', changeActivation)
                    .on('click','.js-office-address-btn',clickAddressBtn)
                    .on('click','.js-eliminar-director',clickDelete);
            });
        };

        this.crear = function() {
            $('#idoficina').select2({
                'language': 'es'
            });
        };

        this.editar = function() {
            $('#idoficina').select2({
                'language': 'es'
            });
        };

        this.crear_equipo = edicionEquipos;
        this.editar_equipo = edicionEquipos;
    });

    $.AdminLTE.srController('oficina', function() {
        this.listado = function() {

            var oDatatable = $.AdminLTE.simpleRouting.dsDatatable;

            oDatatable.run('#oficinaTable', function (dataTable) {

                var changeActivation = function (e) {
                    e.preventDefault();

                    var $this = $(this),
                        newStatus = 0;

                    if($this.hasClass('activar')) {
                        newStatus = 1;
                    }

                    var ajaxSuccess = function(data) {
                        if(data.status == 'ok') {
                            if($this.hasClass('activar')) {
                                $this.removeClass('activar fa-square-o').addClass('desactivar fa-check-square-o');
                            } else {
                                $this.addClass('activar fa-square-o').removeClass('desactivar fa-check-square-o');
                            }
                            dataTable.api().draw(false);
                        } else {
                            alert(data.error);
                        }
                    };

                    $.ajax({
                        url: $this.parent().attr('href'),
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            newStatus: newStatus
                        }
                    }).success(ajaxSuccess);
                };

                $(document.body).on('click', '#oficinaTable .activar', changeActivation)
                    .on('click', '#oficinaTable .desactivar', changeActivation);
            });
        };
    });

    $.AdminLTE.srController('presentacion', function() {

        var getFormData = function($form) {
            var director                = {},
                gestor                  = {},
                gestion_comercial       = {},
                gestion_administrativa  = {},
                otros_contactos         = {},

                nombreEmpresa = $form.find('input[name="nombre_empresa"]').val(),
                idEquipo = $form.find('input[name="idequipo"]:checked').val(),
                generarOficina = $form.find('input[name="generaroficina"]:checked').val();

            var eachDirector = function (x, elem) {
                var $elem = $(elem);
                director[$elem.attr('name')] = $elem.val();
            };

            var eachGestor = function (x, elem) {
                var $elem = $(elem);
                gestor[$elem.attr('name')] = $elem.val();
            };

            var parseData = function(preffix, data) {
                data = typeof data == 'undefined' ?  {} : data;
                var $elem = this,
                    name = $elem.attr('name'),
                    regEx = new RegExp("^" + preffix + "_(\\w+)_(\\d+)$","gi"),
                    matchName = regEx.exec(name),
                    id = matchName[2],
                    nombre = matchName[1];

                if(typeof data[id] == 'undefined') {
                    data[id] = {};
                }
                data[id][nombre] = $elem.val();

                return data;

            };

            var eachGestionAdministrativa = function(x ,elem) {
                var $elem = $(elem);
                gestion_administrativa = parseData.apply($elem,['ga',gestion_administrativa]);
            };

            var eachOtrosContactos = function(x ,elem) {
                var $elem = $(elem);
                otros_contactos = parseData.apply($elem,['ot',otros_contactos]);
            };

            var eachGestionComercial = function(x ,elem) {
                var $elem = $(elem);
                gestion_comercial = parseData.apply($elem,['gc',gestion_comercial]);
            };

            var cleanData = function(datos) {
                var dato;

                for(dato in datos) {
                    var hasData = false;
                    for(var campo in datos[dato]) {
                        if(datos[dato][campo] != '') {
                            hasData = true;
                            break;
                        }
                    }
                    if(!hasData) {
                        delete datos[dato];
                    }
                }

                return datos;
            };

            $form.find('.js-director .form-control').each(eachDirector);
            $form.find('.js-gestor .form-control').each(eachGestor);
            $form.find('.js-gestion-comercial .form-control').each(eachGestionComercial);
            $form.find('.js-gestion-administrativa .form-control').each(eachGestionAdministrativa);
            $form.find('.js-otros-contactos .form-control').each(eachOtrosContactos);

            gestion_comercial = cleanData(gestion_comercial);
            gestion_administrativa = cleanData(gestion_administrativa);
            otros_contactos = cleanData(otros_contactos);

            var formData = {
                'generaroficina': generarOficina,
                'nombre_empresa': nombreEmpresa,
                'idequipo': idEquipo,
                'director': director,
                'gestor': gestor,
                'gestion_comercial': gestion_comercial,
                'gestion_administrativa': gestion_administrativa,
                'otros_contactos': otros_contactos
            };

            return formData;
        };

        this.generar = function() {

            var $generarOficinaBtn = $('input[name="generaroficina"]'),
                $paginaOficina = $('.js-pagina-oficina'),
                $generarBtn = $('#submitbutton'),
                $loadingIcon = $('.js-loading'),
                $downloadLinks = $('#download_links');

            $generarOficinaBtn.click(function() {
                $paginaOficina.slideToggle();
            });

            $generarBtn.click(function(e) {
                e.preventDefault();

                if(!$generarBtn.hasClass('disabled')) {

                    $loadingIcon.removeClass('hide');

                    $generarBtn.addClass('disabled');

                    var $form = $('#Gestor_Form_PresentacionForm');
                    var actionUrl = $form.attr('action');

                    var formData = getFormData($form);

                    var successFn = function () {

                        $generarBtn.removeClass('disabled');
                        $loadingIcon.addClass('hide');

                        $downloadLinks.removeClass('hide');

                    };

                    $.AdminLTE.simpleRouting.ajax.run({
                        url: actionUrl,
                        data: formData
                    }, successFn);
                }
            });

            var checkCounter = function(action) {
                var $addBtn = this,
                    counter = parseInt($addBtn.data('counter')),
                    functionName, textButton;

                counter = (action == 'remove') ? counter - 1 : counter + 1;
                $addBtn.data('counter',counter);

                textButton = counter == 0 ? $addBtn.data('text-empty-counter') : $addBtn.data('text-init-counter');
                $addBtn.text(textButton);

                functionName = counter == parseInt($addBtn.data('max-counter')) ? 'addClass' : 'removeClass';
                $addBtn[functionName]('hide');
            };

            var getFormBlockTemplate = function() {
                return  "<div class='form-group'>" +
                    "<label class='col-sm-2 control-label'>{label}</label>" +
                    "<div class='col-sm-10'>" +
                    "<input type='text' name='{prefijo}_{campo}_{counter}' id='{prefijo}_{campo}_{counter}' class='form-control' value=''/>" +
                    "</div>" +
                    "</div>"
            };

            var addFormBlock = function(counter, prefijo) {

                var $wrapper = this;

                var campos = {
                    'nombre': 'Nombre',
                    'telefono': 'Teléfono',
                    'email': 'Correo Electrónico'
                };

                var $formBlock = $("<div class='form-block'></div>");

                for(var campo in campos) {
                    var tpl = getFormBlockTemplate();
                    tpl = tpl.replace(/\{label\}/g,campos[campo]);
                    tpl = tpl.replace(/\{campo\}/g,campo);
                    tpl = tpl.replace(/\{counter\}/g,counter);
                    tpl = tpl.replace(/\{prefijo\}/g, prefijo);

                    var $formGroup = $(tpl);

                    $formGroup.appendTo($formBlock);
                }

                $formBlock.append(
                    "<div class='form-group'>" +
                    "<label class='col-sm-2 control-label'>&nbsp</label>" +
                    "<div class='col-sm-10'>" +
                    "<button class='btn btn-xs " + prefijo + " btn-danger js-remove'>Eliminar</button>" +
                    "</div>" +
                    "</div>"
                );

                $formBlock.appendTo($wrapper);
            };

            var $addOtrosContactosBtn        = $('button.js-add.ot'),
                $addGestionAdministrativaBtn = $('button.js-add.ga'),
                $addGestionComercial         = $('button.js-add.gc');


            $(document.body).on('click', '.js-remove.ot', function (e) {
                e.preventDefault();
                $(this).parents('.form-block').remove();
                checkCounter.apply($addOtrosContactosBtn, ['remove']);

            }).on('click','.js-remove.ga', function (e) {
                e.preventDefault();
                $(this).parents('.form-block').remove();
                checkCounter.apply($addGestionAdministrativaBtn, ['remove']);
            }).on('click','.js-remove.gc', function (e) {
                e.preventDefault();
                $(this).parents('.form-block').remove();
                checkCounter.apply($addGestionComercial, ['remove']);
            });


            $addOtrosContactosBtn.click(function (e) {
                e.preventDefault();
                var $this = $(this),
                    counter = parseInt($this.data('counter'));

                addFormBlock.apply($('.js-otros-contactos > div:first'),[counter, 'ot']);
                checkCounter.apply($this, ['add']);
            });

            $addGestionAdministrativaBtn.click(function (e) {
                e.preventDefault();
                var $this = $(this),
                    counter = parseInt($this.data('counter'));

                addFormBlock.apply($('.js-gestion-administrativa > div:first'),[counter, 'ga']);
                checkCounter.apply($this, ['add']);
            });

            $addGestionComercial.click(function (e) {
                e.preventDefault();
                var $this = $(this),
                    counter = parseInt($this.data('counter'));

                addFormBlock.apply($('.js-gestion-comercial > div:first'),[counter, 'gc']);
                checkCounter.apply($this, ['add']);
            });
        };
    });

    $.AdminLTE.srController('estadisticas', function() {

        this.mostrar = function() {

            var $rangoFechas = $('#rangoFechas'),
                $dateRangeBtn = $('#daterange-btn');

            var oDatatable = $.AdminLTE.simpleRouting.dsDatatable.run('#estadisticasTable',{
                serverSide: false,
                ajax: {
                    'data': function(d) {
                        d.fechas = $rangoFechas.val()
                    }
                }
            });

            // Funciones de fecha
            $rangoFechas.daterangepicker({
                format: 'DD/MM/YYYY',
                locale: {
                    applyLabel: 'Aplicar',
                    cancelLabel: 'Cancelar',
                    fromLabel: 'Desde',
                    toLabel: 'Hasta',
                    weekLabel: 'Semana',
                    customRangeLabel: 'Rango personalizado',
                    daysOfWeek: moment.weekdaysMin(),
                    monthNames: moment.monthsShort(),
                    firstDay: moment.localeData()._week.dow
                }
            });

            //Date range as a button
            $dateRangeBtn.daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            });

            var clickRangoFecha = function (e) {
                e.preventDefault();
                oDatatable.ajax.reload();
            };
            var clickExportarEstadisticas = function(e) {
                e.preventDefault();
                var datosFiltrados = oDatatable.rows({ search:'applied' }).data();

                var aDatos = [], i;

                for(i = 0; i < datosFiltrados.length; i++) {
                    aDatos.push(datosFiltrados[i]);
                }

                $("#estadisticasDatos").val(JSON.stringify(aDatos));

                $('#formExport').submit();
            };
            $('#btnEstadisticasRangoFecha').on('click', clickRangoFecha);
            $('#btnExportarEstadisticas').on('click', clickExportarEstadisticas);
        };
    });
});


$(document).ready(function () {

    moment.locale('es');

    $.AdminLTE.srRoutes({
        '/home': function() {},
        '/especialistas/substitute/{:num}':       ['especialistas','sustituir'],
        '/especialistas':                         ['especialistas','listado'],
        '/director_relacion':                     ['director_relacion','listado'],
        '/director_relacion/add':                 ['director_relacion','crear'],
        '/director_relacion/edit/{:num}':         ['director_relacion','editar'],
        '/director_relacion/equipos/{:num}':      ['director_relacion','editar_equipo'],
        '/director_relacion/crearequipos/{:num}': ['director_relacion','crear_equipo'],
        '/oficina':                               ['oficina','listado'],
        '/presentacion':                          ['presentacion', 'generar'],
        '/estadisticas':                          ['estadisticas','mostrar']
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

