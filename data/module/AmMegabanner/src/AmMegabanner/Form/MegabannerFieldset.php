<?php

namespace AmMegabanner\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use Administrator\Validator\IsImage;
use AmMegabanner\Model\MegabannerTable;

class MegabannerFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = MegabannerTable::class;

    public function addElements()
    {
        $elementUrl = $this->get('elementUrl');
        $class = $elementUrl->getAttribute('class');
        $class .= ' browsefile';
        $elementUrl->setAttribute('class', $class);
        $elementUrl->setAttribute('readonly', 'readonly');
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['elementUrl']['filters'][] = [
            'name' => MediaUri::class
        ];

        $inputFilter['elementUrl']['validators'][] = [
            'name' => IsImage::class
        ];

        return $inputFilter;
    }
}
