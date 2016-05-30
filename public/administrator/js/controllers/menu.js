$(function () {
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