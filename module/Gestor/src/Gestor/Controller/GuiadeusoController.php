<?php
namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

use Gestor\Form\GuiadeusoForm;


class GuiadeusoController extends AuthController implements ControllerInterface
{
    protected $equipoTable;

    public function setControllerVars()
    {
        $this->formService      = $this->sm->get('Gestor\Service\GestorFormService'); //->setTable($this->especialistaTable);

    }


    public function indexAction()
    {

        // Con una acción tenemos de sobra

        // Seteamos los datos base del form
        $guiadeusoForm = new GuiadeusoForm();
        $guiadeusoForm->setServiceLocator($this->getServiceLocator());
        $guiadeusoForm->addFields();

        $this->formService->setForm($guiadeusoForm);
        $form = $this->formService->getForm();

        $guiadeusoForm->get('submit')->setAttribute('value', 'Subir guía de uso');



        $config = $this->sm->get('Config');
        $pathUpload = $this->config['pathbase_upload'].$config['path_guia'];
        $nombreFichero = $this->config['nombre_guia'];


        // Si nos lo han subido
        $request = $this->getRequest();
        if ($request->isPost()) {
            // Vamos a ver si la extensión es pdf
            if (isset($_FILES['pdf']['name']))
            {
                $nombreOrigen = $_FILES['pdf']['name'];
                $extension = substr($nombreOrigen,(strlen($nombreOrigen) - 3), 3);
                if (($extension == 'pdf') || ($extension == 'PDF'))
                {
                    // Copiamos el fichero donde corresponde y con su nuevo nombre $nombreFichero
                    $errorUpload = $this->sm->get('Gestor\Service\FileUploadService')->UploadFile($pathUpload, $nombreFichero, $_FILES['pdf']);

                    if (($errorUpload == '') || ($errorUpload == null)) { $uploadCorrecto = 'Fichero PDF subido correctamente.'; }
                } else {
                    $errorUpload = 'El fichero ha de tener extensión PDF';
                }
            } else {
                $errorUpload = 'No se subió ningún fichero';
            }



        }

        return new ViewModel(array(
            'errorUpload'       => $errorUpload,
            'uploadCorrecto'    => $uploadCorrecto,
            'pathUpload'        => $pathUpload,
            'nombreFichero'     => $nombreFichero,
            'form'              => $guiadeusoForm,
            'path_guia'         => $config['path_guia'],
        ));

    }
}

