<?php

namespace AmJob\Model;

use Administrator\Model\AdministratorModel;

class JobModel extends AdministratorModel
{
    public function setImageUrl($value)
    {
        $this->imageUrl = json_decode($value);
    }
}