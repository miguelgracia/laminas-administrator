<?php

namespace Api\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject;

class StaticPageService implements FactoryInterface
{
    protected $table;
    protected $tableLocale;

    protected $cacheData = array();

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
        if (array_key_exists(__FUNCTION__, $this->cacheData)) {
            return $this->cacheData[__FUNCTION__];
        }

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

        $result = new ArrayObject(array(
            'rows' => $rows,
            'locale' => $locales
        ),ArrayObject::ARRAY_AS_PROPS);

        $this->cacheData[__FUNCTION__] = $result;

        return $result;
    }
}