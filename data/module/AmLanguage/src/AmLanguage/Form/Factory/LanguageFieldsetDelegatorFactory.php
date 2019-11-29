<?php

namespace AmLanguage\Form\Factory;

use AmLanguage\Model\LanguageTable;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

class LanguageFieldsetDelegatorFactory implements DelegatorFactoryInterface
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = LanguageTable::class;

    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $fieldset = $callback($options);

        $permissions = $container->get('AmProfile\Service\ProfilePermissionService');

        if ($permissions->isAdmin()) {
            return $fieldset;
        }

        $fieldset->get('name')->setAttribute('readonly','readonly');
        $fieldset->get('code')->setAttribute('readonly','readonly');

        $active = $fieldset->get('active');

        $activeLabelAttributes = $active->getOptions();

        $activeLabelAttributes['label_attributes']['class'] .= ' hide';

        $active->setOptions($activeLabelAttributes);

        $activeElemClasses = explode(' ',$active->getAttribute('class'));

        $active->setAttribute('class',implode(' ',array_merge($activeElemClasses,array('hide'))));

        $fieldset->get('order')->setAttribute('readonly','readonly');

        return $fieldset;
    }
}