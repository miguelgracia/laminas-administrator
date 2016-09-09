<?php

namespace Api\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject;

class AppDataService implements FactoryInterface
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
        $this->table       = $serviceLocator->get('AmAppData\Model\AppDataTable');
        $this->tableLocale = $serviceLocator->get('AmAppData\Model\AppDataLocaleTable');

        return $this;
    }

    public function getData ()
    {
        $row = $this->table->find('app-data','key');

        $locales = $this->tableLocale->findLocales($row->id)->setFetchGroupResultSet('languageCode');
        $localeObj = new \stdClass();
        $locales->toObjectArray($localeObj);

        return new ArrayObject(array(
            'row' => $row->getObjectCopy(),
            'locale' => $localeObj
        ),ArrayObject::ARRAY_AS_PROPS);

    }
}