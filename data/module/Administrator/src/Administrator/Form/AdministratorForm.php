<?php


namespace Administrator\Form;

use Zend\Form\Form;

class AdministratorForm extends Form
{
    const ACTION_DEFAULT    = 'index';
    const ACTION_ADD        = 'add';
    const ACTION_EDIT       = 'edit';
    const ACTION_DELETE     = 'delete';
}