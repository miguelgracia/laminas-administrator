<?php

namespace AmJob\Model;

use Administrator\Model\AdministratorModel;

class JobModel extends AdministratorModel
{
    public function getImageUrl()
    {
        return json_decode($this->imageUrl);
    }
}