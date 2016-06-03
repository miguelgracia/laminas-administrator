<?php

namespace AmBlog\Controller;

use Administrator\Controller\AuthController;
use AmBlog\Form\BlogForm;

class AmBlogModuleController extends AuthController
{
    protected $form = BlogForm::class;
}