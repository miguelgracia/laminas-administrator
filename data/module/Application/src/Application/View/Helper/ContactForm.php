<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class ContactForm extends AbstractHelper
{
    protected $form;

    protected $helperManager;

    function __invoke($form)
    {
        $this->form = $form;

        return $this;
    }

    public function getFormWrapper()
    {
        $formHelper = $this->getView()->getHelperPluginManager()->get('Form');


        return $formHelper->openTag($this->form) . '%s' . $formHelper->closeTag();
    }

    public function getFormGroupWrapper()
    {
        return "<div class='form-group'>
                    %s
                    %s
                    %s
                </div>";
    }

    public function getLabelWrapper()
    {
        return "<label for='%s'>%s</label>";
    }

    public function renderError($field)
    {
        $messageTranslations = array(
            'email' => array(
                'isEmpty' => 'Value is required and can\'t be empty',
                'emailAddressInvalidHostname' => 'Invalid email format',
                'emailAddressInvalidFormat'   => 'Invalid email format',
                'hostnameInvalidHostname'     => 'Invalid email format',
                'hostnameLocalNameNotAllowed' => 'Invalid email format',
            ),
            'message' => array(
                'isEmpty' => 'Value is required and can\'t be empty',
            ),
            'legal' => array(
                'isEmpty' => 'Value is required and can\'t be empty',
            )
        );

        $fieldId = $field->getAttribute('id');
        $errorMessages = $field->getMessages();


        $messages = array();
        $li = '';
        foreach ($errorMessages as $keyMessage => $message) {
            if (array_key_exists($fieldId, $messageTranslations)) {
                $fieldTranslations = $messageTranslations[$fieldId];
                if (array_key_exists($keyMessage, $fieldTranslations)) {
                    if (!in_array($fieldTranslations[$keyMessage], $messages)) {
                        $newMessage = $this->translator->translate($fieldTranslations[$keyMessage],'frontend');
                        $messages[] = $fieldTranslations[$keyMessage];
                        $li .= "<li>" . $newMessage . "</li>";
                    }
                } else {
                    $li .= "<li>".$this->translator->translate($message)."</li>";
                }
            }
        }

        $ul = '<ul class="error">';
        $ul .= $li;
        $ul .= '</ul>';

        return $ul;
    }

    public function render($lang)
    {
        $helperManager = $this->getView()->getHelperPluginManager();
        $formInput = $helperManager->get('formInput');
        $formTextarea = $helperManager->get('formTextarea');
        $formCheckbox = $helperManager->get('formCheckbox');
        $url = $helperManager->get('Url');

        //echo $this->translate($elementError($fieldset->get('name')),'frontend');

        $this->form->prepare();
        $this->form->setAttribute('action',$url($lang.'/contact'));

        $fieldset = $this->form->get('contact');

        $formTag = $this->getFormWrapper();
        $groupTags = '';

        $label = sprintf($this->getLabelWrapper(),'name', $this->translator->translate('Form Name','frontend'));
        $input = $formInput($fieldset->get('name'));
        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, $input, '' );

        $label = sprintf($this->getLabelWrapper(),'email', $this->translator->translate('Form Email','frontend'));
        $field = $fieldset->get('email');
        $input = $formInput($field);
        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, $input, $this->renderError($field));

        $label = sprintf($this->getLabelWrapper(),'phone', $this->translator->translate('Form Phone','frontend'));
        $input = $formInput($fieldset->get('phone'));
        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, $input, '' );

        $label = sprintf($this->getLabelWrapper(),'message', $this->translator->translate('Form Message','frontend'));
        $field = $fieldset->get('message');
        $input = $formTextarea($field);
        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, $input, $this->renderError($field) );

        $field = $fieldset->get('legal');

        $input = $formCheckbox($field);
        $label = sprintf($this->getLabelWrapper(),'legal',
            ($input . "He leído y acepto los <a href='".$url($lang)."'>términos y condiciones de uso<a>")
            );
        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, '', $this->renderError($field) );

        $groupTags .= "<button type='submit' class='btn btn-default'>".$this->translator->translate('Form Send','frontend')."</button>";

        $html = sprintf($formTag,$groupTags);

        echo $html;
    }
}