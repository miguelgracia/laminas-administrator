<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 1/20/2016
 * Time: 4:25 PM
 */

namespace Gestor\Listener;

use Gestor\Service\CrudService;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class CrudListener implements ListenerAggregateInterface
{
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $this->listeners[] = $sharedEvents->attach('Gestor\Service\GestorFormService','create',array($this,'onCreate'),100);
        $this->listeners[] = $sharedEvents->attach('Gestor\Service\GestorFormService','read',array($this,'onRead'),100);
        $this->listeners[] = $sharedEvents->attach('Gestor\Service\GestorFormService','update',array($this,'onUpdate'),100);
        $this->listeners[] = $sharedEvents->attach('Gestor\Service\GestorFormService','delete',array($this,'onDelete'),100);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function onCreate(Event $eventManager)
    {
        $formService = $eventManager->getParam('formService');
        $serviceLocator = $formService->getServiceLocator();

        $request = $serviceLocator->get('Request');

        $controllerPluginManager = $serviceLocator->get('ControllerPluginManager');

        $controller = $controllerPluginManager->getController();

        $model = $formService->getTableEntityModel();

        // Metemos los validadores
        $form = $formService->getForm();

        $form->setInputFilter($model->getInputFilter());

        // Bindeamos al formulario lo que nos ha llegado por post.
        $form->bind($request->getPost());

        // Validamos el formulario
        if ($request->isPost()) {

            if ($form->isValid()) {

                //  Si funciona, metemos en el Model a través de su papá GestorModel, que implementa un exchangeArray
                // genérico que nos vale para cualquier colección de datos.
                $model->exchangeArray($form->getData());

                // Ahora ya sí, llamamos al método que hace el INSERT específico

                $insertId = $formService->getTableGateway()->save($model);

                return $controller->goToSection($formService->getRouteParams('section'),array(
                    'action'  => 'edit',
                    'id'      => $insertId
                ));
            }
        }

        return true;
    }

    public function onRead(Event $eventManager)
    {

    }

    public function onUpdate(Event $eventManager)
    {
        $formService = $eventManager->getParam('formService');
        $serviceLocator = $formService->getServiceLocator();

        $controllerPluginManager = $serviceLocator->get('ControllerPluginManager');

        $controller = $controllerPluginManager->getController();

        $request = $serviceLocator->get('Request');

        try {
            $id = $formService->getRouteParams('id');
            $model = $formService->getTableGateway()->getRow($id);
        } catch (\Exception $ex) {
            return $controller->goToSection($formService->getRouteParams('section'));
        }

        $form = $formService->getForm();

        $form->bind($model);

        if ($request->isPost()) {
            $form->setInputFilter($model->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $formService->getTableGateway()->save($model);
                return $controller->goToSection($formService->getRouteParams('section'),array(
                    'action'  => 'edit',
                    'id'      => $id
                ));
            }
        }

        return $id;
    }

    public function onDelete(Event $eventManager)
    {

    }
}