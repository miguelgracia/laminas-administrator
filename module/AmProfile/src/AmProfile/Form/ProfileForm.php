<?php

namespace AmProfile\Form;

use Zend\Form\Form;

class ProfileForm extends Form {

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'gestorPerfilIdPadre' => 'Select',
                'descripcion'   => 'textarea'
            ),
            'fieldValueOptions' => array(
                'gestorPerfilIdPadre' => function ($serviceLocator) {

                    $formService = $serviceLocator->get('Administrator\Service\AdministratorFormService');
                    $misPermisos = $serviceLocator->get('Administrator\Factory\PermisosCheckerFactory');
                    $perfilTable = $serviceLocator->get('AmProfile\Model\ProfileTable');

                    if ($misPermisos->isAdmin()) {
                        $perfilList = $perfilTable->fetchWhere(array('es_admin' => 0))->toKeyValueArray('id','nombre');
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