<?php

namespace Administrator\Model;

use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class ModuleTable extends AdministratorTable
{
    protected $table = "modules";

    public function all()
    {
        $this->select(function (Select $select) {
            $select->columns(array(
                'module_name' => 'name',
                'module_visible' => 'visible'
            ))
            ->join(
               'modules_groups',
               'modules_groups.id = modules.group_id'
            )->join(
               'modules_groups_locales',
               'modules_groups_locales.modules_group_id = modules_groups.id',
               array('module_group_name' => 'name')
            )->where(array(
                'modules.active' => '"1"',
                'modules_groups.active' => '"1"'
            ));
        });
    }

}