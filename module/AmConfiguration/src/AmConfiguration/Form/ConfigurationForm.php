<?php
namespace AmConfiguration\Form;

use Zend\Captcha;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;


class ConfigurationForm extends Form
{
    protected $arrayListado;

    public function __construct($arrayListado)
    {
        parent::__construct();
        $this->arrayListado = $arrayListado;
        $this->setAttribute('class', 'form-horizontal');
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        // Creamos din�micamente el elemento
        foreach ($this->arrayListado as $elementoConfiguracion) {

            // Vamos a ponerle el valor de entryKey como nombre
            $entryKey = $elementoConfiguracion->getEntryKey();
            $entryValue = $elementoConfiguracion->getEntryValue();

            $elementType = stripos($entryKey, 'image') !== false
                ? 'File'
                : 'Text';

            $this->add(array(
                'type' => $elementType,
                'name' => $entryKey,
                'label' => $entryKey,
                'options' => array(
                    'label' => $entryKey,
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label'
                    )
                ),
                'attributes' => array(
                    'value' => $entryValue,
                    'id' => $entryKey,
                    'class' => 'form-control',
                )
            ));
        }
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();

        foreach ($this->arrayListado as $elementoConfiguracion) {

            $entryKey = $elementoConfiguracion->getEntryKey();

            if (stripos($entryKey, 'image') !== false) {
                // File Input
                $input = new FileInput($entryKey);
                $input->setRequired(false);
                $input->getFilterChain()->attachByName(
                    'filerenameupload',
                    array(
                        'randomize' => false,
                        'overwrite' => true,
                        'target'    => "./public/gestor/img/gestor/temp/$entryKey.jpg",
                    )
                );
            } else {
                $input = new Input($entryKey);
                $input->setRequired(true);
            }
            $inputFilter->add($input);
            $this->setInputFilter($inputFilter);
        }
    }
}