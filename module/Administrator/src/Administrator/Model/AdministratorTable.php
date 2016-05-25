<?php

namespace Administrator\Model;


use Administrator\Model\AdministratorResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Metadata\Metadata;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Hydrator\ClassMethods;

abstract class AdministratorTable extends AbstractTableGateway implements AdapterAwareInterface
{
    protected $serviceLocator;

    protected $entityModelName;

    protected $entityModelGeneric;

    function __construct()
    {
        //extraemos el nombre de la clase del que hereda AdministratorTable y quitarmos los 5 últimos
        //caracteres (que corresponderán a la palabra Table). De esta forma podemos llamar al modelo

        $this->entityModelName = (substr(get_class($this),0, -5)) . 'Model';
    }


    /**
     * Instancia el service locator. Esta funci�n se llama desde el inicializador que hay en module.php
     *
     * @param $sm
     */
    public function setServiceLocator($sm)
    {
        $this->serviceLocator = $sm;
    }

    /*
     *  Este método construye el nombre del modelo con el Model al final, para crear una instancia de ese
     * model. Es decir, que se trata de una especie de factor�a abstracta que va a devolver una instancia
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

    public function isTableRow($id, $fieldKey = 'id')
    {
        $id  = (int) $id;
        return $this->select(array($fieldKey => $id))->count() > 0;
    }

    public function getRow($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function deleteRow($id)
    {
        $this->delete(array('id' => (int) $id));
    }


    public function save($data, $id = 0, $fieldKey = 'id')
    {
        if ($id == 0) {
            $this->insert($data);
            $id = $this->getLastInsertValue();
        } else {
            if ($this->isTableRow($id, $fieldKey)) {
                $this->update($data, array($fieldKey => $id));
            } else {
                throw new \Exception($this->table . ' ' . $fieldKey .' id does not exist');
            }
            $this->update($data, array($fieldKey => $id));
        }
        return $id;
    }
}