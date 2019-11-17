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
        $serviceLocator = $this->serviceLocator;

        $formService = $serviceLocator->get('Administrator\Service\AdministratorFormService');

        if ($formService->getForm()->getActionType() == 'add') {
            $padre = (int) $formService->getRouteParams('id');

            $fieldset = $formService->getBaseFieldset();

            $fieldset->get('parent')->setValue($padre);
        }
    }
}