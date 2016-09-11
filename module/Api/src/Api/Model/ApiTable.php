<?php

namespace Api\Model;

use Application\Model\MyOrmTableTrait;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;

class ApiTable extends AbstractTableGateway implements AdapterAwareInterface
{
    use MyOrmTableTrait;

    protected $entityModelName = ApiModel::class;

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