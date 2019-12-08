<?php

namespace AmBlog\Model;

use Administrator\Model\AdministratorModel;

class BlogModel extends AdministratorModel
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
