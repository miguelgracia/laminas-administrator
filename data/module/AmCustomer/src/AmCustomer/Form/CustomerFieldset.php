<?php

namespace AmCustomer\Form;

use Administrator\Form\AdministratorFieldset;
use AmCustomer\Model\CustomerTable;
use Laminas\Db\Metadata\Object\ColumnObject;

class CustomerFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = CustomerTable::class;

    protected function getFilterSpecs(ColumnObject $column)
    {
        $filters = parent::getFilterSpecs($column);

        $columnName = $column->getName();

        if ($columnName == 'key') {
            $filterClassArray = array_column($filters, 'name');

            $slugFilterIdInArray = array_search('Administrator\Filter\SlugFilter', $filterClassArray);

            if (is_numeric($slugFilterIdInArray)) {
                $filters[$slugFilterIdInArray]['options']['separator'] = '/';
            }
        }

        return $filters;
    }
}
