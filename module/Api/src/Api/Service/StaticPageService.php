<?php

namespace Api\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject;

class StaticPageService implements FactoryInterface
{
    protected $table;
    protected $tableLocale;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->table       = $serviceLocator->get('AmStaticPage\Model\StaticPageTable');
        $this->tableLocale = $serviceLocator->get('AmStaticPage\Model\StaticPageLocaleTable');

        return $this;
    }

    public function getData()
    {
        $rows = new ArrayObject(
            $this->table->all(array(
                'active' => '1',
                'deleted_at' => null
            ))->setFetchGroupResultSet('id')->toObjectArray(),
            ArrayObject::ARRAY_AS_PROPS
        );

        $locales = new ArrayObject(
            $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode','urlKey')->toObjectArray(),
            ArrayObject::ARRAY_AS_PROPS
        );

        return new ArrayObject(array(
            'rows' => $rows,
            'locale' => $locales
        ),ArrayObject::ARRAY_AS_PROPS);
    }
}