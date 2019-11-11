<?php


namespace Api\Service;


use Zend\Db\Adapter\AdapterAwareInterface;

interface AllowDatabaseAccessInterface
{
    public function setTable(AdapterAwareInterface $table);

    public function setTableLocale(AdapterAwareInterface $tableLocale);

    public function getTable();

    public function getTableLocale();

    public function setTableName($tableName);

    public function setTableLocaleName($tableLocaleName);

    public function getTableName();

    public function getTableLocaleName();
}