<?php

namespace AmPartner\Model;

use Administrator\Model\AdministratorTable;

class PartnerTable extends AdministratorTable
{
    protected $table = 'partners';

    public const ENTITY_MODEL_CLASS =  PartnerModel::class;
}