<?php
namespace Gestor\Controller;

use Zend\Filter\File\Rename;

use Gestor\Model\ValoresConfiguracion;  // Para poder acceder a los objetos del modelo

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

use Gestor\Form\ConfigurationForm;

class ConfigurationController extends AuthController implements ControllerInterface
{
    protected $valoresConfiguracionTable;

    public function setControllerVars()
    {
        $this->valoresConfiguracionTable = $this->sm->get('Gestor\Model\ValoresConfiguracionTable');

        //$table = $this->valoresConfiguracionTable->getTable();
//        $this->formService = $this->sm->get('Gestor\Service\GestorFormService')->setTable($table);

        $this->formService = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->valoresConfiguracionTable);
    }

    public function indexAction()
    {
        //  Sacamos todos los valores de la tabla de configuracion. Ojo que solo se puede
        // iterar una vez, si no te mola lo pasas a un array :P
        $listado = $this->valoresConfiguracionTable->fetchAll();

        //$this->formService->setSourceForm($listado);

        //$form = $this->formService->setForm(new GestorControladorForm())->addFields();

        // Montamos el formulario base
        $form = new ConfigurationForm($listado->toObjectArray());

        $tempFile = null;

        $prg = $this->fileprg($form);

        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg; // Return PRG redirect response
        } elseif (is_array($prg)) {
            if ($form->isValid()) {
                $formData = $form->getData();

                // Iteramos sobre el contenido del objeto de parámetros, porque vamos a salvar N registros
                foreach($formData as $postEntryKey => $postParamValue)
                {
                    // Cargamos el objeto con lo que queremos grabar

                    $serviceLocator = $this->getServiceLocator();
                    $objetoDatos = $serviceLocator->create('Gestor\Model\ValoresConfiguracionModel');

                    $objetoDatos->setEntryKey($postEntryKey);

                    $canSave = true;
                    // $postParamValue puede llegar a devolver información del fichero que hemos subido.
                    // Dicha información viene en formato Array, por lo que debemos controlar que lo que pasamos
                    // a la vista sea un String

                    if (stripos($postEntryKey,'image') !== false) {
                        if (is_array($postParamValue)) {
                            $filter = new Rename(array(
                                'randomize' => false,
                                'overwrite' => true,
                                'target'    => "./public/gestor/img/gestor/images/$postEntryKey.jpg",
                            ));
                            $filter->filter($postParamValue);
                            $postParamValue = $postParamValue['name'];
                            $objetoDatos->setEntryValue($postParamValue);
                        } else {
                            $canSave = false;
                        }
                    } else {
                        $objetoDatos->setEntryValue($postParamValue);
                    }

                    if ($canSave) {
                        // Grabamos los valores cargados al objeto de datos a través de la clase gateway
                        $this->valoresConfiguracionTable->saveValoresConfiguracion($objetoDatos);
                    }
                }
                return $this->goToSection('configuration');
            } else {
                // Form not valid, but file uploads might be valid...
                // Get the temporary file information to show the user in the view
                $tempFile = array();
                $elements = $form->getElements();
                foreach ($elements as $entryKey => &$formElement) {
                    $fileErrors = $formElement->getMessages();
                    if (stripos($entryKey, 'image') !== false) {
                        if (empty($fileErrors)) {
                            $tempFile[$entryKey] = $formElement->getValue();
                        }
                        $value = $formElement->getValue();
                        if (is_array($value)) {
                            $formElement->setValue('');
                        }
                    }
                }
            }
        }

        return array(
            'form'     => $form,
            'tempFile' => $tempFile,
        );
    }
}