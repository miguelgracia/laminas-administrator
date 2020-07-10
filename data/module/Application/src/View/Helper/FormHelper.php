<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class FormHelper extends AbstractHelper
{
    protected $form;
    protected $fieldsetName = 'contact';

    protected $helperManager;

    protected $url;

    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    public function setFieldsetName($fieldsetName)
    {
        $this->fieldsetName = $fieldsetName;
        return $this;
    }

    protected function setHelpers()
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
        return "<div class='form-group' data-name='%s'>
                    <div class='col-lg-offset-2 col-lg-10'>
                        %s
                        %s
                        <div class='messages'></div>
                      </div>
                </div>";
    }

    public function getLabelWrapper($srOnly = true,  $isRequired = false)
    {
        $class = $srOnly ? 'sr-only' : '';
        return "<label class='$class' for='%s'>%s" . ($isRequired ? ' *' : '') . '</label>';
    }

    protected function setLegalLink($lang, $legal)
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

    protected function getElement($helperName, $field, $labelText = false)
    {
        $fieldset = $this->form->get($this->fieldsetName);

        $formHelper = $this->helperManager->get($helperName);

        $label = is_string($labelText)
            ? sprintf($this->getLabelWrapper(), $field, $this->translator->translate($labelText, 'frontend'))
            : '';

        $field = $fieldset->get($field);
        $input = $formHelper($field);

        return sprintf($this->getFormGroupWrapper(), $field->getName(), $label, $input);
    }
}
