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
            ->setAttribute('class', 'form-horizontal')
            ->setAttribute('action', $urlHelper('administrator', $routeParams));

        $this->addDefaultFields($form, $routeParams['action']);

        return $form;
    }

    /**
     *  Añadimos los elementos de formulario que en principio deben aparecer por defecto
     *  Dicha función se ejecute desde el servicio GestorFormService
     *
     * @param $form
     * @param $actionType
     */
    private function addDefaultFields(&$form, $actionType)
    {
        $actionType = $actionType == $form::ACTION_ADD ? 'Add' : 'Edit';

        $form->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => $actionType,
                'id' => 'submitbutton',
                'class' => 'btn btn-primary',
            ),
            'options' => array(
                'label' => $actionType,
            )
        ),array(
            'priority' => '-9999'
        ));
    }
}