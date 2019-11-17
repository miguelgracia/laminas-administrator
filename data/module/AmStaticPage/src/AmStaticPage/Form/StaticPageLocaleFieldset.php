<?php

namespace AmStaticPage\Form;

use Administrator\Form\AdministratorFieldset;
use AmStaticPage\Model\StaticPageLocaleTable;
use Zend\ServiceManager\ServiceLocatorInterface;

class StaticPageLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = StaticPageLocaleTable::class;

    public function addFields()
    {
        $metaDescription = $this->get('metaDescription');
        $classes = $metaDescription->getAttribute('class');
        $metaDescription->setAttribute('class', $classes . ' no-editor');
    }
}