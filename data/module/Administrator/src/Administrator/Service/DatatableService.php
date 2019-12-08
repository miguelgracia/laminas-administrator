<?php

namespace Administrator\Service;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Having;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Filter\Word\SeparatorToSeparator;
use Zend\Filter\Word\UnderscoreToSeparator;

class DatatableService
{
    protected $config;

    protected $fields = [];
    protected $headerFields = [];

    protected $columns;
    protected $order;
    protected $pageStart;
    protected $pageLength;
    protected $parseFieldToOrder = [];

    protected $adapter;
    protected $sql;
    protected $select;

    protected $rows = [];
    protected $totalRecords = 0;
    protected $totalRecordsFiltered = 0;
    protected $result;

    protected $translator;

    public function __construct($adapter, $translator)
    {
        $this->adapter = $adapter;
        $this->translator = $translator;

        $this->result = new \stdClass();

        $this->sql = new Sql($adapter);
    }

    public function setConfig($config = [], $columns = [], $order = [])
    {
        $this->config = $config;
        $this->columns = $columns;
        $this->order = $order;

        $fields = [];

        $queryFields = [];

        if (isset($this->config['fields'])) {
            $queryFields = $this->config['fields'];
            /**
             * Recogemos los campos de la tabla principal
             */
            foreach ($this->config['fields'] as $index => $field) {
                if (!is_string($field)) {
                    $field = $index;
                    $this->parseFieldToOrder[] = $field;
                }
                $fields[$this->config['from'] . '.' . $field] = $field;
            }
        }

        /**
         * Recogemos los campos de las tablas con las que haremos join
         */
        if (isset($this->config['join']) and count($this->config['join'])) {
            $joins = $this->config['join'];
            foreach ($joins as $joinParams) {
                if (isset($joinParams[2]) and count($joinParams[2]) > 0) {
                    $auxJoinFields = $joinParams[2];
                    $joinTable = $joinParams[0];
                    $joinFields = [];
                    array_walk($auxJoinFields, function (&$elem, $index) use ($joinTable, &$joinFields) {
                        if (!is_string($elem)) {
                            $elem = $index;
                            $this->parseFieldToOrder[] = $elem;
                        }
                        $joinFields[$joinTable . '.' . $elem] = $elem;
                    });
                    $fields = array_merge($fields, $joinFields);
                }
            }
        }

        $this->setQueryFields($queryFields);

        $this->setColumnHeaders($fields);

        return $this;
    }

    public function setQueryFields($fields)
    {
        $currentFields = $this->fields;
        $fields = array_merge($currentFields, $fields);
        $this->fields = $fields;
        return $this;
    }

    public function setColumnHeaders($fields)
    {
        $translator = $this->translator;

        $spaceSeparator = new UnderscoreToSeparator(' ');
        $dollarSeparator = new SeparatorToSeparator('.', '$');

        $currentFields = $this->headerFields;
        $config = $this->config;
        $fields = $currentFields + $fields;

        array_walk($fields, function (&$elem, $index) use ($translator,$spaceSeparator, $dollarSeparator, $config) {
            $searchable = isset($config['searchable'][$index]) ? $config['searchable'][$index] : true;
            $orderable = isset($config['orderable'][$index]) ? $config['orderable'][$index] : true;

            $elem = [
                'value' => $translator->translate($spaceSeparator->filter($elem)),
                'json_key' => $dollarSeparator->filter($index),
                'options' => [
                    'searchable' => $searchable,
                    'orderable' => $orderable
                ],
                /**
                 * attributes contendrá aquellos atributos que se añadiran
                 * a la etiqueta th de cada columna
                 */
                'attributes' => []
            ];
        });
        if (isset($this->config['columns']) and is_callable($this->config['columns'])) {
            $fields = call_user_func_array($this->config['columns'], [$fields]);
        }

        $this->headerFields = $fields;
        return $this;
    }

    public function getHeaderFields()
    {
        return $this->headerFields;
    }

    public function setFrom($from)
    {
        $this->select = $this->sql->select($from);
        $this->select->quantifier(new Expression('SQL_CALC_FOUND_ROWS'));
        $this->select->columns($this->config['fields']);
        return $this;
    }

    public function setJoin($joins)
    {
        $select = $this->select;

        foreach ($joins as $joinParams) {
            call_user_func_array([$select, 'join'], $joinParams);
        }

        return $this;
    }

    public function setWhere($wheres)
    {
        foreach ($wheres as $where) {
            $this->select->where($where);
        }
        return $this;
    }

    public function setColumnFilters()
    {
        foreach ($this->columns as $column) {
            if ($column['search']['value'] === '') {
                continue;
            }

            $columnName = $column['name'];
            $columnValue = $column['search']['value'];

            /**
             * Coge la parte del string que se encuentra después del último punto
             */
            $fieldName = preg_replace("/(.+)\.(.+)$/", '$2', $columnName);

            //TODO: Implementar un sistema para hacer más flexible el tipo de having (having like, having >, having <...)

            if (isset($this->config['having_fields']) and in_array($fieldName, $this->config['having_fields'])) {
                $this->select->having(function (Having $having) use ($fieldName, $columnValue) {
                    $having->like($fieldName, '%' . $columnValue . '%');
                });
            } else {
                $this->select->where(function (Where $where) use ($columnName, $columnValue) {
                    $where->like($columnName, '%' . $columnValue . '%');
                });
            }
        }
        return $this;
    }

    public function setGroup($groups)
    {
        foreach ($groups as $group) {
            $this->select->group($group);
        }
    }

    public function setOrder()
    {
        $order = [];

        foreach ($this->order as $fieldOrder) {
            $field = (new SeparatorToSeparator('$', '.'))->filter($this->columns[$fieldOrder['column']]['name']);

            /**
             * Coge la parte del string que se encuentra después del último punto
             */
            $auxField = preg_replace("/(.+)\.(.+)$/", '$2', $field);

            /**
             * Si la siguiente condición se cumple es porque el campo por el que vamos a ordenar
             * se forma a través de una expresión, por lo que debemos eliminar el nombre de la tabla
             * para capturar el campo de base de datos.
             */
            if (in_array($auxField, $this->parseFieldToOrder)) {
                $field = $auxField;
            }

            $order[] = $field . ' ' . $fieldOrder['dir'];
        }

        $this->select->order($order);
    }

    public function setPagination($start, $length)
    {
        $this->pageStart = $start;
        $this->pageLength = $length;
    }

    public function foundRows()
    {
        $countRows = $this->adapter->query('SELECT FOUND_ROWS() AS rows', Adapter::QUERY_MODE_EXECUTE)->toArray();
        return $countRows[0]['rows'];
    }

    private function executeCounterQuery($countSelect)
    {
        $selectCount = $this->sql->buildSqlString($countSelect);
        $this->adapter->query($selectCount, Adapter::QUERY_MODE_EXECUTE)->toArray();
    }

    public function runCounterQuery()
    {
        $countSelect = clone $this->select;
        $this->executeCounterQuery($countSelect);
        $this->totalRecords = $this->foundRows();
        return $this;
    }

    public function runCounterQueryWithFilters()
    {
        $countSelect = clone $this->select;
        $this->executeCounterQuery($countSelect);
        $this->totalRecordsFiltered = $this->foundRows();

        return $this->totalRecordsFiltered;
    }

    public function getTotalRecords()
    {
        return $this->totalRecords;
    }

    public function getTotalRecordsFiltered()
    {
        return $this->totalRecordsFiltered;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function runLastQuery($removeLimit = false)
    {
        //TODO: Usar clases de sesión de Zend en cuanto sea posible
        $query = '';
        if (isset($_SESSION['datatable_query'])) {
            $query = $_SESSION['datatable_query'];
            if ($removeLimit) {
                $query = preg_replace('/(LIMIT.+)$/s', '', $query);
            }
        }

        return $this->adapter->query($query, Adapter::QUERY_MODE_EXECUTE);
    }

    private function saveQuery($query)
    {
        $_SESSION['datatable_query'] = $query;
    }

    public function runQuery()
    {
        $selectString = $this->sql->buildSqlString($this->select);

        $rows = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE)->toArray();

        //Guardamos la consulta en sesión para futuros usos de exportación de datos
        $this->saveQuery($selectString);
        $this->parseRows($rows);

        $this->rows = $rows;

        $this->result->recordsTotal = $this->getTotalRecords();
        $this->result->recordsFiltered = $this->getTotalRecordsFiltered();
        $this->result->data = $this->rows;
    }

    public function parseRows(&$rows)
    {
        $fieldKeys = array_keys($this->headerFields);
        $dollarSeparator = new SeparatorToSeparator('.', '$');
        array_walk($fieldKeys, function (&$elem) use ($dollarSeparator) {
            $elem = $dollarSeparator->filter($elem);
        });

        foreach ($rows as &$row) {
            if (isset($this->config['parse_row_data']) and is_callable($this->config['parse_row_data'])) {
                $row = call_user_func_array($this->config['parse_row_data'], [$row]);
            }
            $row = array_combine($fieldKeys, array_values($row));
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getData()
    {
        $this->setFrom($this->config['from']);

        if (isset($this->config['join']) and count($this->config['join'])) {
            $this->setJoin($this->config['join']);
        }

        if (isset($this->config['where'])) {
            $this->setWhere($this->config['where']);
        }

        if (isset($this->config['group'])) {
            $this->setGroup($this->config['group']);
        }

        $this->runCounterQuery();

        $this->setColumnFilters();

        $this->runCounterQueryWithFilters();

        $this->select->offset($this->pageStart)->limit($this->pageLength);

        $this->setOrder();

        $this->runQuery();

        return $this->getResult();
    }
}
