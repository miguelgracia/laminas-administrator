<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class ContactForm extends AbstractHelper
{
    protected $form;

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

    public function getLabelWrapper($isRequired = false)
    {
        return "<label for='%s'>%s".($isRequired ? ' *':'')."</label>";
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

    private function setLegalLink($lang, $legal)
    {
        $legalUris = array_combine(
            array_column($legal['rows'],'key','id'),
            array_column($legal['locale'][$lang],'urlKey','relatedTableId')
        );

        $helperManager = $this->getView()->getHelperPluginManager();

        $url = $helperManager->get('Url');

        return sprintf(
            $this->translator->translate("I agree with the terms and conditions of use",'frontend'),
            $url($lang.'/legal/page',array('page' => $legalUris['privacidad']))
        );
    }

    public function render($lang, $legal)
    {
        $helperManager = $this->getView()->getHelperPluginManager();
        $formInput = $helperManager->get('formInput');
        $formTextarea = $helperManager->get('formTextarea');
        $formCheckbox = $helperManager->get('formCheckbox');
        $url = $helperManager->get('Url');

        $this->form->prepare();
        $this->form->setAttribute('action',$url($lang.'/contact'));

        $fieldset = $this->form->get('contact');

        $formTag = $this->getFormWrapper();
        $groupTags = '';

        $label = sprintf($this->getLabelWrapper(),'name', $this->translator->translate('Form Name','frontend'));
        $input = $formInput($fieldset->get('name'));
        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, $input, '' );

        $label = sprintf($this->getLabelWrapper(true),'email', $this->translator->translate('Form Email','frontend'));
        $field = $fieldset->get('email');
        $input = $formInput($field);
        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, $input, $this->renderError($field));

        $label = sprintf($this->getLabelWrapper(),'phone', $this->translator->translate('Form Phone','frontend'));
        $input = $formInput($fieldset->get('phone'));
        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, $input, '' );

        $label = sprintf($this->getLabelWrapper(true),'message', $this->translator->translate('Form Message','frontend'));
        $field = $fieldset->get('message');
        $input = $formTextarea($field);
        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, $input, $this->renderError($field) );

        $field = $fieldset->get('legal');

        $input = $formCheckbox($field);

        $termAndConditionsStr =  $this->setLegalLink($lang,$legal);

        $label = sprintf($this->getLabelWrapper(),'legal',
            ($input . $termAndConditionsStr));
        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, '', $this->renderError($field) );

        $groupTags .= "<button type='submit' class='btn btn-default'>".$this->translator->translate('Form Send','frontend')."</button>";

        $html = sprintf($formTag,$groupTags);

        echo $html;
    }
}