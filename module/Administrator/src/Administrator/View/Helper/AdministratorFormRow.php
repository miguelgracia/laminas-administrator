<?php

namespace Administrator\View\Helper;

use Administrator\Form\AdministratorFieldset;
use Zend\Form\Form;
use Zend\View\Helper\AbstractHelper;

class AdministratorFormRow extends AbstractHelper
{
    protected $serviceLocator;

    protected $view;
    protected $label;
    protected $elementError;
    protected $formElement;

    protected $elementTemplates = array();

    function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator->getServiceLocator();
    }

    private function formTemplate()
    {
        $template = '<div class="box-body">%s</div><div class="box-footer">%s</div>';
        return $template;
    }

    private function printTemplate($elementType = false, $params)
    {
        switch ($elementType) {
            case 'hidden':
            case 'button':
            case 'submit':
                $template = '%2$s%3$s';
                break;
            case 'checkbox':
                $template = '
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                %2$s
                                </label>
                            </div>
                            %3$s
                        </div>
                     </div>';
                break;
            case 'multi_checkbox':
                $template = '
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox multi-checkbox">
                                %2$s
                            </div>
                            %3$s
                        </div>
                     </div>';
                break;
            default:
                $template = "<div class='form-group'>
                        %s
                        <div class='col-sm-10'>
                            %s
                            %s
                        </div>
                     </div>";

        }

        array_unshift($params,$template);

        return call_user_func_array('sprintf',$params);
    }

    public function renderFieldset($fieldset)
    {
        $elements = $fieldset->getElements();
        $render = "";

        foreach ($elements as $element) {
            $render .= $this->render($element);
        }

        return $render;
    }
    public function render($formElement)
    {
        if ($formElement instanceof Form) {
            $boxBody = "";
            $boxFooter = "";

            $form = $formElement;
            foreach ($form as $element) {
                if ($element instanceof AdministratorFieldset) {
                    $boxBody .= $this->renderFieldset($element);
                } else {
                    $elementHtml = $this->render($element);
                    if (in_array($element->getAttribute('type'), array('submit', 'button', 'hidden'))) {
                        $boxFooter .= $elementHtml;
                    } else {
                        $boxBody .= $elementHtml;
                    }
                }
            }
            return sprintf($this->formTemplate(),$boxBody,$boxFooter);
        }

        $label = $this->label;
        $elementError = $this->elementError;

        $elementType = $formElement->getAttribute('type');

        switch ($elementType) {
            case 'textarea':
                $input = $this->view->formTextarea($formElement);
                break;
            case 'select':
                $input = $this->view->formSelect($formElement);
                break;
            case 'checkbox':
                $input = $this->view->formRow($formElement,'append');
                break;
            case 'multi_checkbox':
                $input = $this->view->formRow($formElement,'append');
                break;
            case 'text':
            default:
                $input = $this->view->formInput($formElement);
        }

        return $this->printTemplate($elementType, array(
            $label($formElement),
            $input,
            $elementError($formElement),
        ));
    }

    function __invoke()
    {
        $this->view = $this->getView();

        $this->label = $this->view->plugin('formLabel');

        $this->elementError = $this->view->plugin('formElementErrors');

        $this->elementError
            ->setAttributes(array(
                'class' => 'control-label'
            ))
            //Cambiamos el envoltorio de los mensajes de error
            ->setMessageOpenFormat('<label%s>')
            ->setMessageSeparatorString('')
            ->setMessageCloseString('</label>');

        return $this;
    }
}