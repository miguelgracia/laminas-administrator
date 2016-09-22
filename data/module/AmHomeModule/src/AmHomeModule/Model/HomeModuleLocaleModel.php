<?php

namespace AmHomeModule\Model;

use Administrator\Model\AdministratorModel;

class HomeModuleLocaleModel extends AdministratorModel
{
    public function setImageUrl($value)
    {
        $this->imageUrl = json_decode($value);
    }
}