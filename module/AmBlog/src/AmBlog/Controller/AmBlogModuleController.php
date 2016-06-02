<?php

namespace AmBlog\Controller;

use Administrator\Controller\AuthController;

use Administrator\Model\AdministratorModel;
use AmBlog\Form\BlogForm;

use AmBlog\Form\BlogFieldset;
use AmBlog\Form\BlogLocaleFieldset;

class AmBlogModuleController extends AuthController
{
    protected $form = BlogForm::class;

    protected function setFieldsets($model = false)
    {
        if (!$model instanceof AdministratorModel) {
            $model = $this->tableGateway->getEntityModel();
        }

        $this->formService->addFieldset(BlogFieldset::class, $model);
    }

    protected function setLocaleFieldsets()
    {
        $this->formService->addLocaleFieldsets(BlogLocaleFieldset::class);
    }

}