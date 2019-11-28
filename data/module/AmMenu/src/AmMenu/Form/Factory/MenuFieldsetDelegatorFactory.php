<?php

namespace AmMenu\Form\Factory;

use Administrator\Service\AdministratorFormService;
use AmLanguage\Model\LanguageTable;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

class MenuFieldsetDelegatorFactory implements DelegatorFactoryInterface
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = LanguageTable::class;

    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $fieldset = $callback($options);

        $formService = $container->get(AdministratorFormService::class);

        if ($formService->getActionType() == 'add') {
            $padre = (int) $formService->getRouteParams('id');
            $baseFieldset = $formService->getBaseFieldset();
            $baseFieldset->get('parent')->setValue($padre);
            $baseFieldset->get('order')->setValue('0');
        }

        return $fieldset;
    }
}