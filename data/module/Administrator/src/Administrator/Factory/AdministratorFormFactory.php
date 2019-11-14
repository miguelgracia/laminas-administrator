<?php
namespace Administrator\Factory;

use Interop\Container\ContainerInterface;
use Zend\Filter\Word\SeparatorToSeparator;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdministratorFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $routeParams = $container->get('Application')->getMvcEvent()->getRouteMatch()->getParams();
        $urlHelper = $container->get('ViewHelperManager')->get('url');

        /**
         * Como el nombre del formulario lo seteamos con el nombre de la clase,
         * convertimos el separador de namespace en guiones bajos;
         */
        $formName = (new SeparatorToSeparator('\\','_'))->filter($requestedName);
        $form = (new $requestedName($formName))
            ->setRouteParams($routeParams)
            ->setAttribute('class', 'form-horizontal')
            ->setAttribute('action', $urlHelper('administrator', $routeParams))
            ->setActionType($routeParams['action']);

        return $form;
    }
}