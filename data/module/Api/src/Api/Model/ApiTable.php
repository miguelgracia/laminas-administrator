<?php

namespace Api\Model;

use Application\Model\MyOrmTableTrait;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Predicate\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class ApiTable extends AbstractTableGateway implements AdapterAwareInterface
{
    use MyOrmTableTrait;

    public const ENTITY_MODEL_CLASS = ApiModel::class;

    protected $tableLocaleService = null;

    public function setTableLocaleService($tableLocaleService)
    {
        $this->tableLocaleService = $tableLocaleService;
    }

    private function getSelectForLocale($languageCode = false, $tableFields = [], $localeFields = [], $callback = false)
    {
        $tableLocaleService = $this->tableLocaleService;

        $select = new Select($this->table);
        $where = [];

        $select->columns($tableFields);

        if ($languageCode and $tableLocaleService) {
            $where += [
                'languages.active' => '1',
                'languages.code' => $languageCode,
            ];

            $tableLocale = $tableLocaleService->getTable();

            $select->join(
                $tableLocale,
                new Expression($this->table . '.id =' . $tableLocale . '.related_table_id'),
                $localeFields
            )->join(
                'languages',
                new Expression("languages.id = $tableLocale.language_id"),
                [
                    'language_code' => 'code'
                ],
                Select::JOIN_RIGHT
            );
        }

        if (is_callable($callback)) {
            call_user_func_array($callback, [&$select, &$where]);
        }

        $select->where($where);

        return $select;
    }

    public function allWithLocale($languageCode = false, $tableFields = [], $localeFields = [], $callback = false)
    {
        return $this->selectWith(
            $this->getSelectForLocale($languageCode, $tableFields, $localeFields, $callback)
        );
    }

    public function paginate($languageCode = false, $tableFields = [], $localeFields = [], $callback = false)
    {
        $select = $this->getSelectForLocale($languageCode, $tableFields, $localeFields, $callback);

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

        $resultSet = $this->select(function (Select $select) use ($key, $tableLocale, $languageCode) {
            $select
                ->join(
                    'languages',
                    new Expression("languages.id = $tableLocale.language_id"),
                    [
                        'language_id' => 'id',
                        'language_code' => 'code'
                    ],
                    Select::JOIN_RIGHT
                )->where([
                    'languages.active' => '1',
                    'languages.code' => $languageCode
                ]);
        });

        return $resultSet;
    }

    public function findRow($languageCode, $key, $keyValue, $tableFields = [], $localeFields = [])
    {
        $tableLocaleService = $this->tableLocaleService;

        $select = new Select($this->table);

        $where = [];

        $where[$key] = $keyValue;

        $select->columns($tableFields);

        if ($languageCode and $tableLocaleService) {
            $where += [
                'languages.active' => '1',
                'languages.code' => $languageCode,
            ];

            $tableLocale = $tableLocaleService->getTable();

            $select->join(
                $tableLocale,
                new Expression($this->table . '.id =' . $tableLocale . '.related_table_id'),
                $localeFields
            )->join(
                'languages',
                new Expression("languages.id = $tableLocale.language_id"),
                [
                    'language_code' => 'code'
                ],
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

        $resultSet = $this->select(function (Select $select) use ($id, $key, $tableLocale) {
            $select
                ->join(
                    'languages',
                    new Expression("languages.id = $tableLocale.language_id " . (is_numeric($id) ? "AND $key = $id" : '')),
                    [
                        'language_id' => 'id',
                        'language_code' => 'code'
                    ],
                    Select::JOIN_RIGHT
                )->where([
                    'languages.active' => '1'
                ]);
        });

        return $resultSet;
    }
}
