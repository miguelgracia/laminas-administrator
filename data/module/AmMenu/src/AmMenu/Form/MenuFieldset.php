<?php

namespace AmMenu\Form;

use Administrator\Form\AdministratorFieldset;
use AmMenu\Model\MenuTable;

class MenuFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = MenuTable::class;

    public function addFields()
    {
        //TODO: serviceLocator. Refactorizar eliminando la referencia a serviceLocator
        $formService = $this->serviceLocator->get('Administrator\Service\AdministratorFormService');

        if ($formService->getActionType() == 'add') {
            $padre = (int) $formService->getRouteParams('id');
            $baseFieldset = $formService->getBaseFieldset();
            $baseFieldset->get('parent')->setValue($padre);
            $baseFieldset->get('order')->setValue('0');
        }
    }
}