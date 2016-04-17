<?php
namespace Gestor\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Gestor\Model\GestorControlador;  // Para poder acceder a los objetos del modelo

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

use Gestor\Form\GestorControladorForm;

class GestorControladorController extends AuthController implements ControllerInterface
{
    protected $gestorControladorTable;
    protected $form;

    /**
     * Este método está definido en ControllerInterface y SIEMPRE va a existir. Se lo llamará cuando se crea
     * un controlador, de modo que se hace un setup de aquello que específicamente necesite
     * el controlador.
     */
    public function setControllerVars()
    {
        // Como vamos a acceder a la tabla de controladores sacamos el Model con el Service Manager
        $this->gestorControladorTable = $this->sm->get('Gestor\Model\GestorControladorTable');

        //  Hacemos el setup básico del servicio que apoya al formulario, pasándole el objeto de la tabla
        //  y guardándola en un service genérico de apoyo a formularios.

        //  Además, este método de GestorFormService nos devuelve su propia localización, que vamos a guardar
        // en la clase para poder acceder a él posteriormente.
        $this->formService = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->gestorControladorTable);
    }


    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        // Sacamos el listado completo de controladores del TableGateway
        $arrayListado = $this->gestorControladorTable->fetchAll();

        // Vamos a la vista de índice con ese array para pintar la tabla.
        return new ViewModel(array(
                'controladores' => $arrayListado,
            )
        );
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction()
    {
        //  En $this->formService tenemos el servicio GestorFormService.
        //  Básicamente con esta llamada primero creamos un Form del tipo específico para este controlador
        // (GestorControladorForm()), lo preparamos (->addFields) y luego guardamos el resultado en nuestro
        // servicio formService.
        $this->formService->setForm(new GestorControladorForm())->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        // ¿Estamos guardando los datos del formulario?
        if ($request->isPost()) {

            //  Llamamos a getEntityModel(), definido en GestorTable (de donde hereda el resto de tablas y models),
            //  Básicamente esto nos va a crear una instancia del modelo en sí, en este caso de GestorControladorModel,
            // dando de alta sus caracteristicas básicas (Metadata)
            //  * Retoque: Llamamos a un getGenericModel que monte dinámicamente el modelo sin necesitar de un fichero
            // que lo extienda de manera específica.

            $gestorControlador = $this->gestorControladorTable->getEntityModel();

            // Metemos los validadores
            $form->setInputFilter($gestorControlador->getInputFilter());

            // Bindeamos al formulario lo que nos ha llegado por post.
            $form->bind($request->getPost());

            // Validamos el formulario
            if ($form->isValid()) {

                //  Si funciona, metemos en el Model a través de su papá GestorModel, que implementa un exchangeArray
                // genérico que nos vale para cualquier colección de datos.
                $gestorControlador->exchangeArray($form->getData());

                // Ahora ya sí, llamamos al método que hace el INSERT específico
                $insertId = $this->gestorControladorTable->saveGestorControlador($gestorControlador);

                // Nos vamos a la vista
                return $this->goToSection('gestorcontrolador', array(
                    'action' => 'edit',
                    'id' => $insertId
                ));
            }
        }

        // Si no hay nada que grabar, nos ha valido con crear el formulario.
        return array(
            'form' => $form,
        );
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        try {
            // Sacamos los datos del usuario en concreto
            $id = (int) $this->params()->fromRoute('id', 0); // Lo sacamos de la ruta
            // Sacamos la información del controlador definido por este id
            $gestorControlador = $this->gestorControladorTable->getGestorControlador($id);
        }
        catch (\Exception $ex) {
            return $this->goToSection('gestorcontrolador');
        }

        // Le bindeamos los datos al formulario
        $this->formService->setForm(new GestorControladorForm())->addFields();

        $form = $this->formService->getForm();
        $form->bind($gestorControlador);

        // Sacamos los datos dle request
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($gestorControlador->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                // Metemos los datos que vamos a guardar
                //$gestorControlador->exchangeArray($form->getData());
                $this->gestorControladorTable->saveGestorControlador($gestorControlador);

                return $this->goToSection('gestorcontrolador',array(
                    'action'  => 'edit',
                    'id'      => $request->getPost('id')
                ));
            }
        }

        return array(
            'id' => $id,
            'form' => $form
        );

    }

    /**
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('gestorcontrolador');
        }

        // Borramos con este ID
        $this->gestorControladorTable->deleteGestorControlador($id);

        // Nos vamos al listado general
        return $this->goToSection('gestorcontrolador');
    }
}