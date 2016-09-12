<?php

namespace AmSection\Form;

use Administrator\Form\AdministratorFieldset;
use AmSection\Model\SectionTable;
use Zend\Db\Metadata\Object\ColumnObject;

class SectionFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = SectionTable::class;

    public function initializers()
    {
        return array(
            'fieldValueOptions' => array(
                'visible' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                )
            )
        );
    }

    protected function setFilters(ColumnObject $column)
    {
        $filters = parent::setFilters($column);

        $columnName = $column->getName();

        if ($columnName == 'key') {

            foreach ($filters as &$filter) {
                if ($filter['name'] == 'Administrator\Filter\SlugFilter') {
                    $filter['options']['separator'] = '/';
                    break;
                }
            }
        }

        return $filters;
    }
}