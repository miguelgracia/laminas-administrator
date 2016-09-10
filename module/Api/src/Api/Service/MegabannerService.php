<?php

namespace Api\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject;

class MegabannerService implements FactoryInterface
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
        $this->table       = $serviceLocator->get('AmMegabanner\Model\MegabannerTable');
        $this->tableLocale = $serviceLocator->get('AmMegabanner\Model\MegabannerLocaleTable');

        return $this;
    }

    public function getData($lang)
    {
        $rows = new ArrayObject(

            $this->table->all(array(
                'active' => '1',
                'deleted_at' => null
            ))->setFetchGroupResultSet('id')->toObjectArray(),

            ArrayObject::ARRAY_AS_PROPS
        );

        $locales = $this->tableLocale->findByLangCode($lang)->setFetchGroupResultSet('relatedTableId')->toObjectArray();

        foreach ($rows as &$row) {
            $row->locale = $locales[$row->id];
        }

        return $rows;
    }
}