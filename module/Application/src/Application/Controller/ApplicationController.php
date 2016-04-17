<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 1/4/2016
 * Time: 1:36 PM
 */

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class ApplicationController extends AbstractActionController
{
    public function onDispatch(MvcEvent $e)
    {
        return parent::onDispatch($e);
    }
}