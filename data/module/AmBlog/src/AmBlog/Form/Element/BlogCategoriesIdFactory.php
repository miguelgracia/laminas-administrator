<?php


namespace AmBlog\Form\Element;

use Api\Model\BlogCategoryTable;
use Interop\Container\ContainerInterface;
use Zend\Form\Element\Select;
use Zend\ServiceManager\Factory\FactoryInterface;

class BlogCategoriesIdFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $container->get('FormElementManager')
            ->build(Select::class, $options)
            ->setValueOptions(
                $container->get(BlogCategoryTable::class)->all()->toKeyValueArray('id', 'key')
            );
    }
}