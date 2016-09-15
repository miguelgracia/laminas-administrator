<?php

namespace AmPartner\Model;

use Administrator\Model\AdministratorTable;

class PartnerTable extends AdministratorTable
{
    protected $table = 'partners';

    protected $entityModelName =  PartnerModel::class;
}