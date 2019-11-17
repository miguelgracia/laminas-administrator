<?php

namespace AmSection\Form;

use Administrator\Form\AdministratorFieldset;
use AmSection\Model\SectionTable;
use Zend\Db\Metadata\Object\ColumnObject;

class SectionFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = SectionTable::class;

    protected function setFilters(ColumnObject $column)
    {
        $filters = parent::setFilters($column);

        $columnName = $column->getName();

        if ($columnName == 'key') {

            $filterClassArray = array_column($filters,'name');

            $slugFilterIdInArray = array_search('Administrator\Filter\SlugFilter',$filterClassArray);

            if (is_numeric($slugFilterIdInArray)) {
                $filters[$slugFilterIdInArray]['options']['separator'] = '/';
            }
        }


        return $filters;
    }
}