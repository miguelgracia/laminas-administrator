<?php

namespace AmBlog\Model;

use Administrator\Model\AdministratorModel;

class BlogModel extends AdministratorModel
{
    public function getImageUrl()
    {
        return json_decode($this->imageUrl);
    }
}