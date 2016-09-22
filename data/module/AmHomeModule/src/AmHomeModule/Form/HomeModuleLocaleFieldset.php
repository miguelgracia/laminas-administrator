<?php

namespace AmHomeModule\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use AmHomeModule\Model\HomeModuleLocaleTable;

class HomeModuleLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = HomeModuleLocaleTable::class;

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'content'           => 'Textarea',
            ),
        );
    }

    public function addFields()
    {
        $imageUrl = $this->get('imageUrl');
        $class = $imageUrl->getAttribute('class');
        $class .= ' browsefile';
        $imageUrl->setAttribute('class',$class);

        $imageUrl->setOption('partial_view','am-job/am-job-module/form-partial/image-url');
        $imageUrl->setOption('allow_add_multiple_files', true);
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['imageUrl']['filters'][] = array(
            'name' => MediaUri::class
        );

        return $inputFilter;
    }

    public function getHiddenFields()
    {
        return array(
            'languageId'
        );
    }

}

