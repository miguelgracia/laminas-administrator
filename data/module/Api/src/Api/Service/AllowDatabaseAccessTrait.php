<?php

namespace Api\Service;

use Laminas\Db\Adapter\AdapterAwareInterface;

trait AllowDatabaseAccessTrait
{
    protected $table;
    protected $tableLocale;

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function setTable(AdapterAwareInterface $table): void
    {
        $this->table = $table;
    }

    /**
     * @return mixed
     */
    public function getTableLocale()
    {
        return $this->tableLocale;
    }

    /**
     * @param mixed $tableLocale
     */
    public function setTableLocale(AdapterAwareInterface $tableLocale): void
    {
        $this->tableLocale = $tableLocale;
    }

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->tableName ?? null;
    }

    /**
     * @param mixed $tableName
     */
    public function setTableName($tableName): void
    {
        $this->tableName = $tableName;
    }

    /**
     * @return mixed
     */
    public function getTableLocaleName()
    {
        return $this->tableLocaleName ?? null;
    }

    /**
     * @param mixed $tableLocaleName
     */
    public function setTableLocaleName($tableLocaleName): void
    {
        $this->tableLocaleName = $tableLocaleName;
    }
}
