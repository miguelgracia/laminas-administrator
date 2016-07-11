<?php


namespace Administrator\Form;


use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AdministratorForm extends Form implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
}