<?php

namespace AmAccessory\Model;

use Administrator\Model\AdministratorTable;

class AccessoryLocaleTable extends AdministratorTable
{
    protected $table = 'accessories_locales';

    public const ENTITY_MODEL_CLASS = AccessoryLocaleModel::class;
}
