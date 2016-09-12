<?php

namespace AmPartner\Model;

use Administrator\Model\AdministratorTable;

class PartnerLocaleTable extends AdministratorTable
{
    protected $table = 'partners_locales';

    protected $entityModelName =  PartnerLocaleModel::class;
}