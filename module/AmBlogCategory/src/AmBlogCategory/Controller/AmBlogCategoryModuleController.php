<?php

namespace AmBlogCategory\Controller;

use Administrator\Controller\AuthController;
use AmBlogCategory\Form\BlogCategoryForm;

class AmBlogCategoryModuleController extends AuthController
{
    protected $form = BlogCategoryForm::class;
}