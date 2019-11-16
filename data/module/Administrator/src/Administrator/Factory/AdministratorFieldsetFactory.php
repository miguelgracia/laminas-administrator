<?php
namespace Administrator\Factory;

use Administrator\Service\AdministratorFormService;
use Interop\Container\ContainerInterface;
use Zend\Db\Metadata\Source\Factory;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdministratorFieldsetFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $formService = $container->get(AdministratorFormService::class);

        $fieldset = (new $requestedName($requestedName));

        if ($fieldset->isPrimaryFieldset()) {
            $formService->setBaseFieldset($fieldset);
        }

        return $fieldset
            ->setServiceLocator($container)
            ->setTableGateway($container->get($fieldset->getTableGatewayName()))
            ->setMetadata(Factory::createSourceFromAdapter($container->get('Zend\Db\Adapter\Adapter')))
            ->setObjectModel($options['model'])
            ->setOption('is_locale',false);
    }
}