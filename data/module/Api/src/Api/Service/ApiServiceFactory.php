<?php

namespace Api\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ApiServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new $requestedName($container);

        if ($service instanceof AllowDatabaseAccessInterface) {
            $tableName = $service->getTableName();
            if (class_exists($tableName)) {
                $service->setTable($container->get($tableName));
            }

            $tableLocaleName = $service->getTableLocaleName();

            if (class_exists($tableLocaleName)) {
                $service->setTableLocale($container->get($tableLocaleName));
            }
        }

        return $service;
    }
}