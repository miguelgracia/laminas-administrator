<?php

namespace Gestor\Form;

use Zend\Form\Form;

class PerfilForm extends Form {

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'idPerfilPadre' => 'Select',
                'descripcion'   => 'textarea'
            ),
            'fieldValueOptions' => array(
                'idPerfilPadre' => function ($serviceLocator) {

                    $formService = $serviceLocator->get('Gestor\Service\GestorFormService');
                    $misPermisos = $serviceLocator->get('Gestor\Factory\PermisosCheckerFactory');
                    $perfilTable = $serviceLocator->get('Gestor\Model\PerfilTable');

                    if ($misPermisos->isAdmin()) {
                        $perfilList = $perfilTable->fetchWhere(array('esAdmin' => 0))->toKeyValueArray('id','nombre');
                        $perfilListArray = array('' => "[perfil raiz]") + $perfilList;
                    } else {
                        $perfilList = $perfilTable->fetchHijos($misPermisos->idPerfil)->toKeyValueArray('id','nombre');
                        $perfilList2 = $perfilTable->fetchId($misPermisos->idPerfil)->toKeyValueArray('id','nombre');

                        $perfilListArray = $perfilList2 + $perfilList;
                    }

                    //Si estamos dentro de una url de edición, controlamos que no sea posible
                    if ($formService->getRouteParams('action') == 'edit') {
                        $id = $formService->getRouteParams('id');
                        if (isset($perfilListArray[$id])) {
                            unset($perfilListArray[$id]);
                        }
                    }

                    return $perfilListArray;
                }
            )
        );
    }
}