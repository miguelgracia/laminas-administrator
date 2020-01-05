<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class ContactForm extends AbstractHelper
{
    protected $form;

    protected $helperManager;

    protected $url;

    public function __invoke($form)
    {
        $this->form = $form;

        return $this;
    }

    private function setHelpers()
    {
        $this->helperManager = $this->getView()->getHelperPluginManager();
    }

    public function getFormWrapper()
    {
        $formHelper = $this->getView()->getHelperPluginManager()->get('Form');
        return $formHelper->openTag($this->form) . '%s' . $formHelper->closeTag();
    }

    public function getFormGroupWrapper()
    {
        return "<div class='form-group'>
                    <div class='row'>
                        %s
                        %s
                      </div>
                </div>";
    }

    public function getLabelWrapper($srOnly = true,  $isRequired = false)
    {
        $class = $srOnly ? 'sr-only' : '';
        return "<label class='$class' for='%s'>%s" . ($isRequired ? ' *' : '') . '</label>';
    }

    private function setLegalLink($lang, $legal)
    {
        $rowPrivacityId = array_search('privacidad', array_column($legal['rows'], 'key', 'id'));
        $locale = array_column($legal['locale'][$lang], 'urlKey', 'relatedTableId');

        $helperManager = $this->getView()->getHelperPluginManager();

        $url = $helperManager->get('Url');

        return sprintf(
            $this->translator->translate('I agree with the terms and conditions of use', 'frontend'),
            $url('locale/legal/page', ['locale' => $lang, 'page' => $locale[$rowPrivacityId]])
        );
    }

    private function getElement($helperName, $field, $labelText = false)
    {
        $fieldset = $this->form->get('contact');

        $formHelper = $this->helperManager->get($helperName);

        $label = is_string($labelText)
            ? sprintf($this->getLabelWrapper(), $field, $this->translator->translate($labelText, 'frontend'))
            : '';

        $field = $fieldset->get($field);
        $input = $formHelper($field);

        return sprintf($this->getFormGroupWrapper(), $label, $input);
    }

    public function render($lang, $legal)
    {
        $this->setHelpers();

        $formCheckbox = $this->helperManager->get('formCheckbox');

        $url = $this->helperManager->get('Url');

        $this->form->prepare();
        $this->form->setAttribute('action', $url('locale/contact', ['locale' => $lang]));

        $fieldset = $this->form->get('contact');

        $formTag = $this->getFormWrapper();

        $groupTags = $this->getElement('formInput', 'name', 'Form Name');
        $groupTags .= $this->getElement('formInput', 'email', 'Form Email');
        $groupTags .= $this->getElement('formInput', 'phone', 'Form Phone');
        $groupTags .= $this->getElement('formTextarea', 'message', 'Form Message');
        $groupTags .= $this->getElement('formInput', 'g-recaptcha-response',false);

        $field = $fieldset->get('legal');

        $input = $formCheckbox($field);

        $termAndConditionsStr = $this->setLegalLink($lang, $legal);

        $label = sprintf(
            $this->getLabelWrapper(false),
            'legal',
            ($input . $termAndConditionsStr)
        );

        $groupTags .= sprintf($this->getFormGroupWrapper(), $label, '');

        $groupTags .= "
            <div class='form-group'>
                <div class='row'>
                    <button type='submit' class='btn btn-primary btn-lg'>"
                        . $this->translator->translate('Form Send', 'frontend'). '
                    </button>
                </div>
            </div>'
        ;

        echo sprintf($formTag, $groupTags);
    }
}
