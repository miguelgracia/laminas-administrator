<?php

namespace Api\Model;

use Application\Model\MyOrmTableTrait;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ApiTable extends AbstractTableGateway implements AdapterAwareInterface
{
    use MyOrmTableTrait;

    protected $entityModelName = ApiModel::class;

    protected $tableLocaleService = null;

    public function setTableLocaleService($tableLocaleService)
    {
        $this->tableLocaleService = $tableLocaleService;
    }

    public function paginate($languageCode = false, $tableFields = array(), $localeFields = array(), $callback = false)
    {
        $tableLocaleService = $this->tableLocaleService;

        $select = new Select($this->table);
        $where = array();

        $select->columns($tableFields);

        if ($languageCode and $tableLocaleService) {

            $where += array(
                'languages.active' => '1',
                'languages.code'   => $languageCode,
            );

            $tableLocale = $tableLocaleService->getTable();

            $select->join(
                $tableLocale,
                new Expression($this->table.'.id =' .$tableLocale .'.related_table_id'),
                $localeFields
            )->join(
                'languages',
                new Expression("languages.id = $tableLocale.language_id"),
                array(
                    "language_code" => "code"
                ),
                Select::JOIN_RIGHT
            );
        }

        if (is_callable($callback)) {
            call_user_func_array($callback,array(&$select,&$where));
        }

        $select->where($where);

        $paginatorAdapter = new DbSelect(
            $select,
            $this->adapter,
            $this->resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);

        return $paginator;
    }

    public function findByLangCode($languageCode)
    {
        $tableLocale = $this->table;

        $key = $this->relatedKey;

        $resultSet = $this->select(function (Select $select) use($key, $tableLocale, $languageCode) {
            $select
                ->join(
                    'languages',
                    new Expression("languages.id = $tableLocale.language_id"),
                    array(
                        "language_id" => "id",
                        "language_code" => "code"
                    ),
                    Select::JOIN_RIGHT
                )->where(array(
                    'languages.active' => '1',
                    'languages.code'   => $languageCode
                ));
        });

        return $resultSet;
    }

    public function findRow($languageCode, $key, $keyValue, $tableFields = array(), $localeFields = array())
    {
        $tableLocaleService = $this->tableLocaleService;

        $select = new Select($this->table);

        $where = array();

        $where[$key] = $keyValue;

        $select->columns($tableFields);

        if ($languageCode and $tableLocaleService) {

            $where += array(
                'languages.active' => '1',
                'languages.code'   => $languageCode,
            );

            $tableLocale = $tableLocaleService->getTable();


            $select->join(
                $tableLocale,
                new Expression($this->table.'.id =' .$tableLocale .'.related_table_id'),
                $localeFields
            )->join(
                'languages',
                new Expression("languages.id = $tableLocale.language_id"),
                array(
                    "language_code" => "code"
                ),
                Select::JOIN_RIGHT
            );
        }

        $select->where($where);

        return $this->selectWith($select);
    }

    public function findLocales($id = false)
    {
        $tableLocale = $this->table;

        $key = $this->relatedKey;

        $resultSet = $this->select(function (Select $select) use($id, $key, $tableLocale) {
            $select
                ->join(
                    'languages',
                    new Expression("languages.id = $tableLocale.language_id " . (is_numeric($id) ? "AND $key = $id" : '')),
                    array(
                        "language_id" => "id",
                        "language_code" =>'code'
                    ),
                    Select::JOIN_RIGHT
                )->where(array(
                    'languages.active' => '1'
                ));
        });

        return $resultSet;
    }
}