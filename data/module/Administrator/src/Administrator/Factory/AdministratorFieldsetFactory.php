<?php

namespace Administrator\Factory;

use Administrator\Service\AdministratorFormService;
use Administrator\Service\CheckIdService;
use Administrator\Service\ConfigureFieldsetService;
use Interop\Container\ContainerInterface;
use Laminas\Db\Metadata\Object\ColumnObject;
use Laminas\Db\Metadata\Source\Factory;
use Laminas\Filter\Word\SeparatorToCamelCase;
use Laminas\Form\Element\Hidden;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AdministratorFieldsetFactory implements FactoryInterface
{
    protected $checkIdService;
    protected $formElementManager;
    protected $configureFieldsetService;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controllerPluginManager = $container->get('ControllerPluginManager');

        $this->checkIdService = $container->get(CheckIdService::class);
        $this->formElementManager = $container->get('FormElementManager');
        $this->configureFieldsetService = $container->get(ConfigureFieldsetService::class);

        $formService = $container->get(AdministratorFormService::class);

        $fieldset = (new $requestedName($requestedName));

        if ($fieldset->isPrimaryFieldset()) {
            $formService->setBaseFieldset($fieldset);
        }

        $metadata = Factory::createSourceFromAdapter($container->get('Laminas\Db\Adapter\Adapter'));
        $tableGateway = $container->get($fieldset->getTableGatewayName());
        $columns = $metadata->getColumns($tableGateway->getTable());

        $fieldset->setTableGateway($tableGateway)
            ->setColumns($columns)
            ->setObjectModel($controllerPluginManager->getController()->getModel())
            ->setOption('is_locale', false);

        $this->configureFieldsetService->configure($fieldset);

        return $fieldset;
    }
}
