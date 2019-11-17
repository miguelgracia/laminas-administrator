<?php

namespace AmLanguage\Form;

use Administrator\Form\AdministratorFieldset;
use AmLanguage\Model\LanguageTable;

class LanguageFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = LanguageTable::class;

    public function addFields()
    {
        $permissions = $this->serviceLocator->get('AmProfile\Service\ProfilePermissionService');

        if (!$permissions->isAdmin()) {

            $this->get('name')->setAttribute('readonly','readonly');
            $this->get('code')->setAttribute('readonly','readonly');

            $active = $this->get('active');
            $activeLabelAttributes = $active->getOptions();

            $activeLabelAttributes['label_attributes']['class'] .= ' hide';

            $active->setOptions($activeLabelAttributes);

            $activeElemClasses = explode(' ',$active->getAttribute('class'));

            $active->setAttribute('class',implode(' ',array_merge($activeElemClasses,array('hide'))));

            $this->get('order')->setAttribute('readonly','readonly');

        }
    }


}