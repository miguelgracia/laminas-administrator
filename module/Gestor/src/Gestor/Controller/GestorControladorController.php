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
     * Este m�todo est� definido en ControllerInterface y SIEMPRE va a existir. Se lo llamar� cuando se crea
     * un controlador, de modo que se hace un setup de aquello que espec�ficamente necesite
     * el controlador.
     */
    public function setControllerVars()
    {
        // Como vamos a acceder a la tabla de controladores sacamos el Model con el Service Manager
        $this->gestorControladorTable = $this->sm->get('Gestor\Model\GestorControladorTable');

        //  Hacemos el setup b�sico del servicio que apoya al formulario, pas�ndole el objeto de la tabla
        //  y guard�ndola en un service gen�rico de apoyo a formularios.

        //  Adem�s, este m�todo de GestorFormService nos devuelve su propia localizaci�n, que vamos a guardar
        // en la clase para poder acceder a �l posteriormente.
        $this->formService = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->gestorControladorTable);
    }


    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        // Sacamos el listado completo de controladores del TableGateway
        $arrayListado = $this->gestorControladorTable->fetchAll();

        // Vamos a la vista de �ndice con ese array para pintar la tabla.
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
        //  B�sicamente con esta llamada primero creamos un Form del tipo espec�fico para este controlador
        // (GestorControladorForm()), lo preparamos (->addFields) y luego guardamos el resultado en nuestro
        // servicio formService.
        $this->formService->setForm(new GestorControladorForm())->addFields();

        $form = $this->formService->getForm();

        $request = $this->getRequest();

        // �Estamos guardando los datos del formulario?
        if ($request->isPost()) {

            //  Llamamos a getEntityModel(), definido en GestorTable (de donde hereda el resto de tablas y models),
            //  B�sicamente esto nos va a crear una instancia del modelo en s�, en este caso de GestorControladorModel,
            // dando de alta sus caracteristicas b�sicas (Metadata)
            //  * Retoque: Llamamos a un getGenericModel que monte din�micamente el modelo sin necesitar de un fichero
            // que lo extienda de manera espec�fica.

            $gestorControlador = $this->gestorControladorTable->getEntityModel();

            // Metemos los validadores
            $form->setInputFilter($gestorControlador->getInputFilter());

            // Bindeamos al formulario lo que nos ha llegado por post.
            $form->bind($request->getPost());

            // Validamos el formulario
            if ($form->isValid()) {

                //  Si funciona, metemos en el Model a trav�s de su pap� GestorModel, que implementa un exchangeArray
                // gen�rico que nos vale para cualquier colecci�n de datos.
                $gestorControlador->exchangeArray($form->getData());

                // Ahora ya s�, llamamos al m�todo que hace el INSERT espec�fico
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
            // Sacamos la informaci�n del controlador definido por este id
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