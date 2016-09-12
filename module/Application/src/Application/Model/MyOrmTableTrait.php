<?php

namespace Application\Model;


use Administrator\Model\AdministratorResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\Hydrator\ClassMethods;

trait MyOrmTableTrait
{
    protected $serviceLocator;

    protected $relatedKey = 'related_table_id';

    /**
     * Instancia el service locator. Esta función se llama desde el inicializador que hay en module.php
     *
     * @param $sm
     */
    public function setServiceLocator($sm)
    {
        $this->serviceLocator = $sm;
    }

    /*
     *  Este método construye el nombre del modelo con el Model al final, para crear una instancia de ese
     * model. Es decir, que se trata de una especie de factoría abstracta que va a devolver una instancia
     * de modelo.
     */
    public function getEntityModel()
    {
        $entityModel = $this->serviceLocator->create($this->entityModelName);

        $entityModel->setMetadata(
            new Metadata(
                $this->serviceLocator->get('Zend\Db\Adapter\Adapter')
            ),
            $this->table
        );

        return $entityModel;
    }

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;

        $resultSetPrototype = new AdministratorResultSet();
        $classMethods = new ClassMethods();
        $resultSetPrototype->setHydrator($classMethods);

        $resultSetPrototype->setObjectPrototype($this->getEntityModel());

        $this->resultSetPrototype = $resultSetPrototype;

        $this->initialize();
    }

    public function getRelatedKey()
    {
        return $this->relatedKey;
    }

    public function isTableRow($id, $fieldKey = 'id')
    {
        $id  = (int) $id;
        return $this->select(array($fieldKey => $id))->count() > 0;
    }

    public function all($where = array())
    {
        return $this->select($where);
    }

    public function find($id, $key = 'id')
    {
        $id  = (int) $id;
        $rowset = $this->select(array($key => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id with key $key in table " . $this->table);
        }
        return $row;
    }

    public function findMany($id, $key = 'id')
    {
        $id  = (int) $id;

        $rowset = $this->select(array($key => $id));

        return $rowset;
    }

    public function findLocales($id)
    {
        $tableLocale = $this->table;

        $key = $this->relatedKey;

        $resultSet = $this->select(function (Select $select) use($id, $key, $tableLocale) {
            $select
                ->join(
                    'languages',
                    new Expression("languages.id = $tableLocale.language_id AND $key = $id"),
                    array(
                        "language_id" => "id"
                    ),
                    Select::JOIN_RIGHT
                )->where(array(
                    'languages.active' => '1'
                ))->order('languages.order ASC');
        });

        return $resultSet;
    }

    public function deleteRow($id, $key = 'id')
    {
        return $this->delete(array($key => (int) $id));
    }

    public function deleteSoft($id, $fieldKey = 'id')
    {
        if ($this->isTableRow($id, $fieldKey)) {
            return $this->update(array(
                'deletedAt' => date('Y-m-d H:i:s')
            ), array($fieldKey => $id));
        } else {
            throw new \Exception($this->table . ' ' . $fieldKey .' id does not exist');
        }
    }

}