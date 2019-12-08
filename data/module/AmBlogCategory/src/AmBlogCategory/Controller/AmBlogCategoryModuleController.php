<?php

namespace AmBlogCategory\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\DeleteAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmBlogCategory\Form\BlogCategoryForm;

class AmBlogCategoryModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction, DeleteAction;

    public const FORM_CLASS = BlogCategoryForm::class;
}
