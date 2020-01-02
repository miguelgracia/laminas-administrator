<?php

namespace AmCustomer\Model;

use Administrator\Model\AdministratorTable;

class CustomerTable extends AdministratorTable
{
    protected $table = 'customers';

    public const ENTITY_MODEL_CLASS = CustomerModel::class;
}
