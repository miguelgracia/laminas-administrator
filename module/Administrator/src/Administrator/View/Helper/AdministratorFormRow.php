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

    protected $elementsId = array();

    protected $elementTemplates = array();

    function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator->getServiceLocator();
    }

    private function formTemplate()
    {
        $template = '<div class="box-body">%s%s</div><div class="box-footer">%s</div>';
        return $template;
    }

    private function localeTabTemplate($tplType = '')
    {
        switch ($tplType) {
            case "link":
                $template = "<li class='%s'><a href='#tab_%s' data-toggle='tab'>%s</a></li>";
                break;
            case "tab":
                $template = "<div class='tab-pane %s' id='tab_%s'>%s</div>";
                break;
            default:
                $template = "
                    <div class='nav-tabs-custom'>
                        <ul class='nav nav-tabs'>
                            %s
                        </ul>
                        <div class='tab-content'>
                        %s
                        </div>
                    </div>
                ";
        }

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
        $render = "";

        foreach ($fieldset as $element) {
            $render .= $this->render($element);
        }

        return $render;
    }

    /**
     * @param $formElement
     *
     * Comprobamos que los id's que se han asignado a los elementos de formulario no est�n repetidos
     * Si encontramos un caso en el que s� este, a�adimos un prefijo al id
     */
    private function checkId(&$formElement)
    {
        $id = $formElement->getAttribute('id');
        if (array_key_exists($id, $this->elementsId)) {
            $this->elementsId[$id]++;
            $formElement->setAttribute("id",$id . "_" . $this->elementsId[$id]);
        } else {
            $this->elementsId[$id] = 0;
        }
    }
    public function render($formElement)
    {
        if ($formElement instanceof Form) {
            $boxBody    = "";
            $boxFooter  = "";
            $localeTabs = "";
            $linkTabs   = "";

            $form = $formElement;
            $index = 0;
            foreach ($form as $element) {
                if ($element instanceof AdministratorFieldset) {
                    if ($element->getOption('is_locale')) {
                        $index++;
                        $activeClass = $linkTabs == '' ? 'active' : '';
                        $linkTabs .= sprintf($this->localeTabTemplate('link'),$activeClass,$index,$element->getOption("tab_name"));
                        $localeTabs .= sprintf($this->localeTabTemplate('tab'),$activeClass,$index,$this->renderFieldset($element));
                    } else {
                        $boxBody .= $this->renderFieldset($element);
                    }
                } else {
                    $elementHtml = $this->render($element);
                    if (in_array($element->getAttribute('type'), array('submit', 'button', 'hidden'))) {
                        $boxFooter .= $elementHtml;
                    } else {
                        $boxBody .= $elementHtml;
                    }
                }
            }

            $tabTpl = $localeTabs != '' ? sprintf($this->localeTabTemplate(), $linkTabs, $localeTabs) : '';

            return sprintf($this->formTemplate(),$boxBody,$tabTpl,$boxFooter);
        }

        $this->checkId($formElement);

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