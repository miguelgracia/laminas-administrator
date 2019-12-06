<?php


namespace Administrator\Factory;

use Administrator\Form\AdministratorForm;
use Administrator\Service\AdministratorFormService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdministratorFormServiceFactory implements FactoryInterface
{
    /**
     * @var array
     *
     * Tipos de action de formulario permitidos y su correspondencia con eventos
     */
    protected $allowedActionType = array(
        AdministratorForm::ACTION_ADD        => AdministratorFormService::EVENT_CREATE,
        AdministratorForm::ACTION_DEFAULT    => AdministratorFormService::EVENT_READ,
        AdministratorForm::ACTION_EDIT       => AdministratorFormService::EVENT_UPDATE,
        AdministratorForm::ACTION_DELETE     => AdministratorFormService::EVENT_DELETE,
    );

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $action = $container->get('Application')->getMvcEvent()->getRouteMatch()->getParam('action');

        if (!array_key_exists($action, $this->allowedActionType)) {
            throw new \Exception('Action Type ' . $action . ' not allowed');
        }

        return (new AdministratorFormService(
            $container->get('FormElementManager')
        ));
    }

}