<?php

namespace AmProfile\Form;

use Administrator\Form\AdministratorForm;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Server\Reflection;

class ProfileForm extends AdministratorForm {

    public function initializers()
    {
        $form = $this;

        return array(
            'fieldModifiers' => array(
                'descripcion'   => 'textarea',
                'permisos' => 'MultiCheckbox'
            ),
            'fieldValueOptions' => array(
                'esAdmin' => array(
                    '0' => '0',
                    '1' => '1'
                ),
                'permisos' => function ($sm) {

                    $listaControladores = $sm->get('AmController\Model\ControllerTable')->select();

                    $filterDashToCamelCase = new DashToCamelCase();

                    $hiddenMethods = array(
                        'getMethodFromAction',
                        'notFoundAction'
                    );

                    $controllerActions = array();

                    foreach ($listaControladores as $i => $controller) {

                        $controllerNamespace = '\Am%s\Controller\Am%sModuleController';

                        $controllerName = $filterDashToCamelCase->filter($controller->nombreZend);

                        $class = sprintf($controllerNamespace,$controllerName,$controllerName);

                        if (class_exists($class)) {
                            $reflectionController = Reflection::reflectClass($class);

                            $controllerMethods = $reflectionController->getMethods();

                            $actions = array();

                            foreach ($controllerMethods as $method) {

                                $name = $method->getName();

                                if (stripos($name, 'action') !== false and !in_array($name,$hiddenMethods)) {
                                    $action = preg_replace("/(Action)$/", "$2", $name);
                                    $controllerActions[$controller->nombreZend . '.' .$action] = $controller->nombreUsable . ' ' . $action;
                                }
                            }
                            asort($actions);

                        }
                    }
                    return $controllerActions;
                }
            )
        );
    }

    public function addFields()
    {
        $perm = $this->get('permisos');

        $perm->setAttribute('class', '');

        $perm->setLabelAttributes(array(
            'class' => 'col-sm-4'
        ));

        return $this;
    }
}