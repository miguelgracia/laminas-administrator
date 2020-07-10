<?php

namespace AmJob\Form\Element;

use AmJobCategory\Model\JobCategoryTable;
use Interop\Container\ContainerInterface;
use Laminas\Form\Element\Select;
use Laminas\ServiceManager\Factory\FactoryInterface;

class JobCategoriesIdFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $container->get('FormElementManager')
            ->build(Select::class, $options)
            ->setValueOptions(
                $container->get(JobCategoryTable::class)->all()->toKeyValueArray('id', 'key')
            );
    }
}
