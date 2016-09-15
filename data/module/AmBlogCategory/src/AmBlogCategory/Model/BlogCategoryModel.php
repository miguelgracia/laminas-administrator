<?php

namespace AmBlogCategory\Model;

use Administrator\Model\AdministratorModel;

class BlogCategoryModel extends AdministratorModel
{

    public function prepareToSave()
    {
        $toSaveArray = parent::prepareToSave();

        $toSaveArray['updated_at'] = date('Y-m-d H:i:s');

        return $toSaveArray;
    }

}

