<?php

namespace AmProfile\Form\Element;

use AmModule\Service\ModuleService;
use Interop\Container\ContainerInterface;
use Zend\Form\Element\MultiCheckbox;
use Zend\ServiceManager\Factory\FactoryInterface;

class PermissionsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $container->get('FormElementManager')
            ->build(MultiCheckbox::class, $options)
            ->setValueOptions(
                $container->get(ModuleService::class)->getControllerActionsModules()
            );
    }
}
