<?php

namespace AmAccessory\Form\Element;

use AmAccessoryCategory\Model\AccessoryCategoryTable;
use Interop\Container\ContainerInterface;
use Laminas\Form\Element\Select;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AccessoryCategoriesIdFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $container->get('FormElementManager')
            ->build(Select::class, $options)
            ->setValueOptions(
                $container->get(AccessoryCategoryTable::class)->all()->toKeyValueArray('id', 'key')
            );
    }
}
