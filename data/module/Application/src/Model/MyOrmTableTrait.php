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
    protected $relatedKey = 'related_table_id';

    /**
     * TODO: quizá haya una solución mejor
     * @param $resultSetPrototype
     * @return $this
     */
    public function setResultSetPrototype($resultSetPrototype)
    {
        $this->resultSetPrototype = $resultSetPrototype;
        return $this;
    }

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function getRelatedKey()
    {
        return $this->relatedKey;
    }

    public function isLocaleTable()
    {
        preg_match('/_locales$/', $this->table, $result);
        return count($result) > 0;
    }

    public function isTableRow($id, $fieldKey = 'id')
    {
        $id = (int) $id;
        return $this->select([$fieldKey => $id])->count() > 0;
    }

    public function all($where = [], $orderBy = [])
    {
        return $this->select(function (Select $select) use ($where, $orderBy) {
            $select->where($where);
            $select->order($orderBy);
        });
    }

    public function find($id, $key = 'id')
    {
        $id = (int) $id;
        $rowset = $this->select([$key => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id with key $key in table " . $this->table);
        }
        return $row;
    }

    public function findMany($id, $key = 'id')
    {
        $id = (int) $id;

        $rowset = $this->select([$key => $id]);

        return $rowset;
    }

    public function findLocales($id)
    {
        $tableLocale = $this->table;

        $key = $this->relatedKey;

        $resultSet = $this->select(function (Select $select) use ($id, $key, $tableLocale) {
            $select
                ->join(
                    'languages',
                    new Expression("languages.id = $tableLocale.language_id AND $key = $id"),
                    [
                        'language_id' => 'id'
                    ],
                    Select::JOIN_RIGHT
                )->where([
                    'languages.active' => '1'
                ])->order('languages.order ASC');
        });

        return $resultSet;
    }

    public function deleteRow($id, $key = 'id')
    {
        return $this->delete([$key => (int) $id]);
    }

    public function deleteSoft($id, $fieldKey = 'id')
    {
        if ($this->isTableRow($id, $fieldKey)) {
            return $this->update([
                'deletedAt' => date('Y-m-d H:i:s')
            ], [$fieldKey => $id]);
        } else {
            throw new \Exception($this->table . ' ' . $fieldKey . ' id does not exist');
        }
    }
}
