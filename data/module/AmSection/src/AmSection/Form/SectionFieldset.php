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

            $filterClassArray = array_column($filters,'name');

            $slugFilterIdInArray = array_search('Administrator\Filter\SlugFilter',$filterClassArray);

            if (is_numeric($slugFilterIdInArray)) {
                $filters[$slugFilterIdInArray]['options']['separator'] = '/';
            }
        }


        return $filters;
    }
}