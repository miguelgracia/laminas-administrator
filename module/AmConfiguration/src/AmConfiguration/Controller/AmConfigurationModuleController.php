<?php

namespace AmConfiguration\Controller;

use Administrator\Controller\AuthController;
use AmConfiguration\Form\ConfigurationForm;
use Zend\Filter\File\Rename;

class AmConfigurationModuleController extends AuthController
{
    protected $valoresConfiguracionTable;

    public function setControllerVars()
    {
        $this->valoresConfiguracionTable = $this->sm->get('AmConfiguration\Model\ValoresConfiguracionTable');
    }

    public function indexAction()
    {
        $listado = $this->valoresConfiguracionTable->select();

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

                    $objetoDatos = $this->sm->create('AmConfiguration\Model\ValoresConfiguracionModel');

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