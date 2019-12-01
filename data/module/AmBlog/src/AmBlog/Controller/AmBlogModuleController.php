<?php

namespace AmBlog\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\DeleteAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmBlog\Form\BlogForm;

class AmBlogModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction, DeleteAction;

    public const FORM_CLASS = BlogForm::class;
}