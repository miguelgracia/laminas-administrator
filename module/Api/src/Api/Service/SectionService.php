<?php

namespace Api\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject;

class SectionService implements FactoryInterface
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
        $this->table       = $serviceLocator->get('AmSection\Model\SectionTable');
        $this->tableLocale = $serviceLocator->get('AmSection\Model\SectionLocaleTable');

        return $this;
    }

    public function getMenu()
    {
        $rows = new ArrayObject (
            $this->table->all(array('deleted_at' => null))->setFetchGroupResultSet('key')->toArray(),
            ArrayObject::ARRAY_AS_PROPS
        );

        $locales = new ArrayObject (
            $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode','relatedTableId')->toArray(),
            ArrayObject::ARRAY_AS_PROPS
        );

        return new ArrayObject(array(
            'rows' => $rows,
            'locale' => $locales
        ),ArrayObject::ARRAY_AS_PROPS);
    }


}