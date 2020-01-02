<?php

namespace AmAccessory\Model;

use Administrator\Model\AdministratorModel;

class AccessoryModel extends AdministratorModel
{
    public function setImageUrl($value)
    {
        $this->imageUrl = json_decode($value);
    }

    public function getImageUrl()
    {
        return json_encode($this->imageUrl);
    }
}
